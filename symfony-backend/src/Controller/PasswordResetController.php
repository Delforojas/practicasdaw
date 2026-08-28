<?php

namespace App\Controller;



use App\Entity\PasswordResetToken;
use App\Repository\PasswordResetTokenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;



final class PasswordResetController extends AbstractController
{
    #[Route('/api/password/forgot', name: 'api_password_forgot', methods: ['POST'])]
    public function forgot(
        Request $request, 
        UserRepository $userRepository, 
        PasswordResetTokenRepository $tokenRepository, 
        MailerInterface $mailer, 
        EntityManagerInterface $em,
        #[Autowire('%env(FRONTEND_URL)%')] string $frontendUrl,
        RateLimiterFactory $passwordForgotLimiter
    ): JsonResponse {

        $rateLimit = $passwordForgotLimiter->create($request->getClientIp() ?? 'unknown')->consume();
        if (!$rateLimit->isAccepted()) {
            return $this->json(
                ['error' => 'Demasiadas solicitudes. Inténtalo más tarde.'],
                429,
                ['Retry-After' => (string) max(1, $rateLimit->getRetryAfter()->getTimestamp() - time())]
            );
        }

        
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return $this->json(['error' => 'Email requerido'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['message' => 'Si el email existe, recibirás un correo.']);
        }

        // Creamos el token
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        foreach ($tokenRepository->findBy(['user' => $user]) as $previousToken) {
            $em->remove($previousToken);
        }

        $expiresAt = new \DateTimeImmutable('+1 hour');
        $resetToken = new PasswordResetToken();
        $resetToken->setUser($user)
            ->setToken($tokenHash)
            ->setExpiresAt($expiresAt);

        $em->persist($resetToken);
        $em->flush();


        $resetUrl = rtrim($frontendUrl, '/') . '/reset-password?' . http_build_query(['token' => $token]);

        // Datos del correo
        $emailMessage = (new Email())
            ->from('no-reply@tuapp.com')
            ->to($user->getEmail())
            ->subject('Restablecer contraseña')
            ->text("Haz clic en el siguiente enlace para restablecer tu contraseña: $resetUrl");



        try {
            $mailer->send($emailMessage);
        } catch(\Throwable $e){
            return $this->json(['message' => 'Mailer error', 'error' => $e->getMessage()], 500);
        }
        
        return $this->json(['message' => 'Email enviado si existe una cuenta con ese correo.']);
    }




    #[Route('/api/password/reset', name: 'api_password_reset', methods: ['POST'])]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $em,
        PasswordResetTokenRepository $tokenRepo,
        UserPasswordHasherInterface $passwordHasher,
        RateLimiterFactory $passwordResetLimiter
    ): JsonResponse {

        $rateLimit = $passwordResetLimiter->create($request->getClientIp() ?? 'unknown')->consume();
        if (!$rateLimit->isAccepted()) {
            return $this->json(
                ['error' => 'Demasiados intentos. Inténtalo más tarde.'],
                429,
                ['Retry-After' => (string) max(1, $rateLimit->getRetryAfter()->getTimestamp() - time())]
            );
        }

        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? null;
        $newPassword = $data['password'] ?? null;

        if (!is_string($token) || $token === '' || !is_string($newPassword) || $newPassword === '') {
            return $this->json(['error' => 'Token y nueva contraseña requeridos'], 400);
        }

        if (strlen($newPassword) < 8) {
            return $this->json(['error' => 'La contraseña debe tener al menos 8 caracteres'], 400);
        }

        $resetToken = $tokenRepo->findValidToken($token);

        if (!$resetToken) {
            return $this->json(['error' => 'Token inválido o expirado'], 400);
        }

        $user = $resetToken->getUser();
        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));

        // Eliminar token después de usarlo
        $em->remove($resetToken);
        $em->flush();

        return $this->json(['message' => 'Contraseña actualizada correctamente']);
    }
}
