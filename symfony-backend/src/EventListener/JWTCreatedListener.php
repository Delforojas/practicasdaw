<?php


namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JWTCreatedListener
{
   public function onJWTCreated(JWTCreatedEvent $event): void
   {
       /** @var User $user */


       $user = $event->getUser();


       $payload = $event->getData();


       $payload['username'] = $user->getEmail();
       $payload['roles'] = $user->getRoles();


       $event->setData($payload);
   }


}

