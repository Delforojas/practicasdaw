<?php

namespace App\Controller;



use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Repository\PasswordResetTokenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
        EntityManagerInterface $em
    ): JsonResponse {

        
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return $this->json(['error' => 'Email requerido'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['message' => 'Si el email existe, recibirás un correo.']);
        }

        // Creamos el token
        $token = bin2hex(random_bytes(32));

        $expiresAt = new \DateTimeImmutable('+1 hour');
        $resetToken = new PasswordResetToken();
        $resetToken->setUser($user)
            ->setToken($token)
            ->setExpiresAt($expiresAt);

        $em->persist($resetToken);
        $em->flush();


        // Datos del correo
        $emailMessage = (new Email())
            ->from('no-reply@tuapp.com')
            ->to($user->getEmail())
            ->subject('Restablecer contraseña')
            ->text("Haz clic en el siguiente enlace para restablecer tu contraseña: $token");



        try {
            $mailer->send($emailMessage);
        } catch(Throwable $e){
            return $this->json(['message' => 'Mailer error', 'error' => $e->getMessage()], 500);
        }
        
        return $this->json(['message' => 'Email enviado si existe una cuenta con ese correo.']);
    }




    #[Route('/api/password/reset', name: 'api_password_reset', methods: ['POST'])]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $em,
        PasswordResetTokenRepository $tokenRepo,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        
        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? null;
        $newPassword = $data['password'] ?? null;

        if (!$token || !$newPassword) {
            return $this->json(['error' => 'Token y nueva contraseña requeridos'], 400);
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
