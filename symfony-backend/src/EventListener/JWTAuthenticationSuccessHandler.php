<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class JWTAuthenticationSuccessHandler
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $token = $event->getData()['token'] ?? null;
        if (!$token) return;

        $event->getResponse()->headers->setCookie(
            Cookie::create('authtoken')
                ->withValue($token)
                ->withHttpOnly(true)
                ->withSecure(false)   // true en prod HTTPS
                ->withSameSite('Lax')
                ->withPath('/')
                ->withExpires(strtotime('+10 hours'))
        );
    }
}

