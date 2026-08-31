<?php

namespace App\Tests\Controller;

use App\Controller\ContactController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\CacheStorage;

final class ContactControllerTest extends TestCase
{
    public function testContactRejectsInvalidInput(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::never())->method('send');

        $response = $this->controller()->contact(
            $this->request(['nombre' => 'User', 'email' => 'invalid', 'asunto' => 'Subject', 'mensaje' => 'Message']),
            $mailer,
            $this->limiter('contact-invalid')
        );

        self::assertSame(400, $response->getStatusCode());
        self::assertSame(['message' => 'Solicitud inválida.'], json_decode($response->getContent(), true));
    }

    public function testContactDoesNotExposeMailerErrors(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')->willThrowException(new \RuntimeException('internal mailer details'));

        $response = $this->controller()->contact(
            $this->request(['nombre' => 'User', 'email' => 'user@example.test', 'asunto' => 'Subject', 'mensaje' => 'Message']),
            $mailer,
            $this->limiter('contact-mailer-error')
        );

        self::assertSame(500, $response->getStatusCode());
        self::assertSame(['message' => 'No se pudo procesar la solicitud.'], json_decode($response->getContent(), true));
    }

    public function testContactIsRateLimited(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send');
        $limiter = $this->limiter('contact-rate-limit', 1);

        $request = $this->request(['nombre' => 'User', 'email' => 'user@example.test', 'asunto' => 'Subject', 'mensaje' => 'Message']);
        $controller = $this->controller();
        $controller->contact($request, $mailer, $limiter);
        $response = $controller->contact($request, $mailer, $limiter);

        self::assertSame(429, $response->getStatusCode());
    }

    private function controller(): ContactController
    {
        $controller = new ContactController();
        $controller->setContainer(new Container());

        return $controller;
    }

    private function limiter(string $id, int $limit = 100): RateLimiterFactory
    {
        return new RateLimiterFactory(
            ['id' => $id, 'policy' => 'fixed_window', 'limit' => $limit, 'interval' => '1 hour'],
            new CacheStorage(new ArrayAdapter())
        );
    }

    private function request(array $payload): Request
    {
        return Request::create(
            '/api/contact',
            'POST',
            [],
            [],
            [],
            ['REMOTE_ADDR' => '127.0.0.1'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );
    }
}
