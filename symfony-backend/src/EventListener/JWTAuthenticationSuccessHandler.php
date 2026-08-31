<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class JWTAuthenticationSuccessHandler

{

    public function __construct(
        #[Autowire('%kernel.environment%')] private string $environment
    ) {
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void

    {

        $token = $event->getData()['token'] ?? null;

        if (!$token) {

            return;

        }

        $data = $event->getData();
        unset($data['token']);
        $event->setData($data);

        $event->getResponse()->headers->setCookie(

            Cookie::create('authtoken')

                ->withValue($token)

                ->withHttpOnly(true)

                ->withSecure($this->environment === 'prod')

                ->withSameSite(Cookie::SAMESITE_STRICT)

                ->withPath('/')

                ->withExpires(strtotime('+10 hours'))

        );

    }

}
