<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LogoutController
{
    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function __invoke(): JsonResponse
    {
        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie(
            'authtoken',
            '/',
            null,
            false,
            true,
            Cookie::SAMESITE_LAX
        );

        return $response;
    }
}
