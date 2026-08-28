<?php

namespace App\Tests\Controller;

use App\Controller\PasswordResetController;
use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Repository\PasswordResetTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\CacheStorage;

final class PasswordResetControllerTest extends TestCase
{
    public function testForgotWithExistingEmailStoresHashAndReturnsGenericResponse(): void
    {
        $user = (new User())->setEmail('user@example.test');
        $oldToken = (new PasswordResetToken())->setUser($user)->setToken(hash('sha256', 'old-token'))->setExpiresAt(new \DateTimeImmutable('+1 hour'));
        $storedHash = null;
        $sentToken = null;

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['email' => 'user@example.test'])
            ->willReturn($user);

        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $tokenRepository->expects(self::once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$oldToken]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('remove')->with($oldToken);
        $entityManager->expects(self::once())
            ->method('persist')
            ->with(self::callback(function (PasswordResetToken $token) use (&$storedHash): bool {
                $storedHash = $token->getToken();
                return $storedHash !== null && strlen($storedHash) === 64;
            }));
        $entityManager->expects(self::once())->method('flush');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())
            ->method('send')
            ->with(self::callback(function ($email) use (&$sentToken): bool {
                $matches = [];
                self::assertSame(1, preg_match(
                    '~https://frontend\.test/reset-password\?token=([a-f0-9]{64})~',
                    $email->getTextBody(),
                    $matches
                ));
                $sentToken = $matches[1];
                return true;
            }));

        $response = $this->controller($mailer)->forgot(
            $this->request(['email' => 'user@example.test']),
            $userRepository,
            $tokenRepository,
            $mailer,
            $entityManager,
            'https://frontend.test',
            $this->limiter('forgot-existing')
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            ['message' => 'Email enviado si existe una cuenta con ese correo.'],
            json_decode($response->getContent(), true)
        );
        self::assertSame(hash('sha256', $sentToken), $storedHash);
        self::assertNotSame($sentToken, $storedHash);
    }

    public function testForgotWithUnknownEmailReturnsGenericResponse(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn(null);

        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $tokenRepository->expects(self::never())->method('findBy');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::never())->method('send');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('persist');

        $response = $this->controller($mailer)->forgot(
            $this->request(['email' => 'unknown@example.test']),
            $userRepository,
            $tokenRepository,
            $mailer,
            $entityManager,
            'https://frontend.test',
            $this->limiter('forgot-unknown')
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            ['message' => 'Si el email existe, recibirás un correo.'],
            json_decode($response->getContent(), true)
        );
    }

    public function testResetWithValidTokenHashesInputUpdatesUserAndDeletesToken(): void
    {
        $user = (new User())->setEmail('user@example.test');
        $resetToken = (new PasswordResetToken())
            ->setUser($user)
            ->setToken(hash('sha256', 'valid-token'))
            ->setExpiresAt(new \DateTimeImmutable('+1 hour'));

        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $tokenRepository->expects(self::once())->method('findValidToken')->with('valid-token')->willReturn($resetToken);

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects(self::once())
            ->method('hashPassword')
            ->with($user, 'NewPassword123')
            ->willReturn('hashed-password');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('remove')->with($resetToken);
        $entityManager->expects(self::once())->method('flush');

        $response = $this->controller()->resetPassword(
            $this->request(['token' => 'valid-token', 'password' => 'NewPassword123']),
            $entityManager,
            $tokenRepository,
            $passwordHasher,
            $this->limiter('reset-valid')
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['message' => 'Contraseña actualizada correctamente'], json_decode($response->getContent(), true));
        self::assertSame('hashed-password', $user->getPassword());
    }

    public function testResetRejectsInvalidAndExpiredTokens(): void
    {
        foreach (['invalid-token', 'expired-token'] as $token) {
            $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
            $tokenRepository->expects(self::once())->method('findValidToken')->with($token)->willReturn(null);

            $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
            $passwordHasher->expects(self::never())->method('hashPassword');

            $entityManager = $this->createMock(EntityManagerInterface::class);
            $entityManager->expects(self::never())->method('flush');

            $response = $this->controller()->resetPassword(
                $this->request(['token' => $token, 'password' => 'NewPassword123']),
                $entityManager,
                $tokenRepository,
                $passwordHasher,
                $this->limiter('reset-'.$token)
            );

            self::assertSame(400, $response->getStatusCode());
            self::assertSame(['error' => 'Token inválido o expirado'], json_decode($response->getContent(), true));
        }
    }

    public function testResetRejectsPasswordShorterThanEightCharacters(): void
    {
        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $tokenRepository->expects(self::never())->method('findValidToken');

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects(self::never())->method('hashPassword');

        $response = $this->controller()->resetPassword(
            $this->request(['token' => 'valid-token', 'password' => 'short']),
            $this->createMock(EntityManagerInterface::class),
            $tokenRepository,
            $passwordHasher,
            $this->limiter('reset-short')
        );

        self::assertSame(400, $response->getStatusCode());
        self::assertSame(['error' => 'La contraseña debe tener al menos 8 caracteres'], json_decode($response->getContent(), true));
    }

    public function testResetTokenCannotBeReusedAfterSuccessfulReset(): void
    {
        $user = (new User())->setEmail('user@example.test');
        $resetToken = (new PasswordResetToken())
            ->setUser($user)
            ->setToken(hash('sha256', 'single-use-token'))
            ->setExpiresAt(new \DateTimeImmutable('+1 hour'));

        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $tokenRepository->expects(self::exactly(2))
            ->method('findValidToken')
            ->with('single-use-token')
            ->willReturnOnConsecutiveCalls($resetToken, null);

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects(self::once())->method('hashPassword')->willReturn('hashed-password');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('remove')->with($resetToken);
        $entityManager->expects(self::once())->method('flush');

        $firstResponse = $this->controller()->resetPassword(
            $this->request(['token' => 'single-use-token', 'password' => 'NewPassword123']),
            $entityManager,
            $tokenRepository,
            $passwordHasher,
            $this->limiter('reset-reuse')
        );
        $secondResponse = $this->controller()->resetPassword(
            $this->request(['token' => 'single-use-token', 'password' => 'NewPassword123']),
            $entityManager,
            $tokenRepository,
            $passwordHasher,
            $this->limiter('reset-reuse')
        );

        self::assertSame(200, $firstResponse->getStatusCode());
        self::assertSame(400, $secondResponse->getStatusCode());
    }

    public function testForgotReturns429AfterFiveRequestsFromTheSameIp(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn(null);
        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $mailer = $this->createMock(MailerInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $limiter = $this->limiter('forgot-rate-limit', 5, '1 hour');

        for ($attempt = 1; $attempt <= 5; ++$attempt) {
            $response = $this->controller($mailer)->forgot(
                $this->request(['email' => 'unknown@example.test']),
                $userRepository,
                $tokenRepository,
                $mailer,
                $entityManager,
                'https://frontend.test',
                $limiter
            );
            self::assertSame(200, $response->getStatusCode());
        }

        $response = $this->controller($mailer)->forgot(
            $this->request(['email' => 'unknown@example.test']),
            $userRepository,
            $tokenRepository,
            $mailer,
            $entityManager,
            'https://frontend.test',
            $limiter
        );

        self::assertSame(429, $response->getStatusCode());
        self::assertTrue($response->headers->has('Retry-After'));
    }

    public function testResetReturns429AfterTenRequestsFromTheSameIp(): void
    {
        $tokenRepository = $this->createMock(PasswordResetTokenRepository::class);
        $tokenRepository->expects(self::exactly(10))->method('findValidToken')->willReturn(null);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $limiter = $this->limiter('reset-rate-limit', 10, '15 minutes');

        for ($attempt = 1; $attempt <= 10; ++$attempt) {
            $response = $this->controller()->resetPassword(
                $this->request(['token' => 'invalid-token', 'password' => 'NewPassword123']),
                $this->createMock(EntityManagerInterface::class),
                $tokenRepository,
                $passwordHasher,
                $limiter
            );
            self::assertSame(400, $response->getStatusCode());
        }

        $response = $this->controller()->resetPassword(
            $this->request(['token' => 'invalid-token', 'password' => 'NewPassword123']),
            $this->createMock(EntityManagerInterface::class),
            $tokenRepository,
            $passwordHasher,
            $limiter
        );

        self::assertSame(429, $response->getStatusCode());
        self::assertTrue($response->headers->has('Retry-After'));
    }

    public function testResetTokenReportsExpiration(): void
    {
        $expiredToken = (new PasswordResetToken())->setExpiresAt(new \DateTimeImmutable('-1 second'));
        $validToken = (new PasswordResetToken())->setExpiresAt(new \DateTimeImmutable('+1 hour'));

        self::assertTrue($expiredToken->isExpired());
        self::assertFalse($validToken->isExpired());
    }

    private function controller(?MailerInterface $mailer = null): PasswordResetController
    {
        $controller = new PasswordResetController();
        $controller->setContainer(new Container());

        return $controller;
    }

    private function limiter(string $id, int $limit = 100, string $interval = '1 hour'): RateLimiterFactory
    {
        return new RateLimiterFactory(
            ['id' => $id, 'policy' => 'fixed_window', 'limit' => $limit, 'interval' => $interval],
            new CacheStorage(new ArrayAdapter())
        );
    }

    private function request(array $payload): Request
    {
        return Request::create(
            '/api/password/reset',
            'POST',
            [],
            [],
            [],
            ['REMOTE_ADDR' => '127.0.0.1'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );
    }
}
