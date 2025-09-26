<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Aquí podemos validar $data['nombre'], $data['email'] etc.

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to('destino@tusitio.com') // correo del admin
            ->subject('Nuevo formulario de evento')
            ->text(
                "Nombre: {$data['nombre']}\n" .
                "Teléfono: {$data['telefono']}\n" .
                "Email: {$data['email']}\n" .
                "Evento: {$data['evento']}\n" .
                "Fecha: {$data['fecha']}\n" .
                "Mensaje: {$data['mensaje']}\n"
            );

        $mailer->send($email);

        return new JsonResponse(['status' => 'ok']);
    }
}
