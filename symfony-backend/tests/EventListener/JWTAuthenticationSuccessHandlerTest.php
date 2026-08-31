<?php

namespace App\Tests\EventListener;

use App\EventListener\JWTAuthenticationSuccessHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

final class JWTAuthenticationSuccessHandlerTest extends TestCase
{
    public function testTokenIsKeptOnlyInTheCookie(): void
    {
        $response = new JsonResponse(['token' => 'jwt-token']);
        $event = new AuthenticationSuccessEvent(
            ['token' => 'jwt-token'],
            $this->createMock(UserInterface::class),
            $response
        );

        (new JWTAuthenticationSuccessHandler('prod'))->onAuthenticationSuccess($event);

        self::assertArrayNotHasKey('token', $event->getData());
        $cookie = $response->headers->getCookies()[0];
        self::assertSame('authtoken', $cookie->getName());
        self::assertTrue($cookie->isHttpOnly());
        self::assertTrue($cookie->isSecure());
        self::assertSame('/', $cookie->getPath());
        self::assertSame('strict', $cookie->getSameSite());
    }
}
