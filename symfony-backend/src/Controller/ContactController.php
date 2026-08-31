<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer, RateLimiterFactory $contactLimiter): JsonResponse
    {
        $rateLimit = $contactLimiter->create($request->getClientIp() ?? 'unknown')->consume();
        if (!$rateLimit->isAccepted()) {
            return $this->json(
                ['message' => 'Solicitud no disponible temporalmente.'],
                429,
                ['Retry-After' => (string) max(1, $rateLimit->getRetryAfter()->getTimestamp() - time())]
            );
        }

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['message' => 'Solicitud inválida.'], 400);
        }

        $name = $this->validatedString($data['nombre'] ?? null, 150);
        $emailAddress = $this->validatedString($data['email'] ?? null, 254);
        $subject = $this->validatedString($data['asunto'] ?? ($data['evento'] ?? null), 150);
        $message = $this->validatedString($data['mensaje'] ?? null, 5000);

        if ($name === null || $emailAddress === null || $subject === null || $message === null
            || filter_var($emailAddress, FILTER_VALIDATE_EMAIL) === false) {
            return $this->json(['message' => 'Solicitud inválida.'], 400);
        }

        $phone = $this->validatedString($data['telefono'] ?? '', 40) ?? '';
        $date = $this->validatedString($data['fecha'] ?? '', 40) ?? '';

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to('destino@tusitio.com') // correo del admin
            ->subject($subject)
            ->text(
                "Nombre: {$name}\n" .
                "Teléfono: {$phone}\n" .
                "Email: {$emailAddress}\n" .
                "Asunto: {$subject}\n" .
                "Fecha: {$date}\n" .
                "Mensaje: {$message}\n"
            );

        try {
            $mailer->send($email);
        } catch (\Throwable) {
            return $this->json(['message' => 'No se pudo procesar la solicitud.'], 500);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    private function validatedString(mixed $value, int $maxLength): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '' || mb_strlen($value) > $maxLength) {
            return null;
        }

        return $value;
    }
}
