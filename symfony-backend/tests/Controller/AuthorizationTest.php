<?php

namespace App\Tests\Controller;

use App\Entity\Enum\RoleEnum;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthorizationTest extends WebTestCase
{
    public function testProtectedEndpointRejectsUnauthenticatedRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/me');

        self::assertSame(401, $client->getResponse()->getStatusCode());
    }

    public function testAdminEndpointRejectsRegularUser(): void
    {
        $client = static::createClient();
        $user = $this->persistUser(RoleEnum::USER, 'security-user');

        try {
            $client->request(
                'POST',
                '/api/teacher',
                [],
                [],
                ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token($user)],
                json_encode($this->teacherPayload(), JSON_THROW_ON_ERROR)
            );

            self::assertSame(403, $client->getResponse()->getStatusCode());
        } finally {
            $this->removeUser($user);
        }
    }

    public function testAdminEndpointAllowsAdministrator(): void
    {
        $client = static::createClient();
        $admin = $this->persistUser(RoleEnum::ADMIN, 'security-admin');

        try {
            $client->request(
                'POST',
                '/api/teacher',
                [],
                [],
                ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token($admin)],
                json_encode($this->teacherPayload(), JSON_THROW_ON_ERROR)
            );

            self::assertSame(201, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        } finally {
            $this->removeUserByEmail('teacher@example.test');
            $this->removeUser($admin);
        }
    }

    private function persistUser(RoleEnum $role, string $username): User
    {
        $user = (new User())
            ->setFullName('Security Test')
            ->setUserName($username)
            ->setEmail($username.'@example.test')
            ->setRole($role)
            ->setCreatedAt(new \DateTimeImmutable());
        $user->setPassword(static::getContainer()->get(UserPasswordHasherInterface::class)->hashPassword($user, 'Password123'));

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    private function removeUser(User $user): void
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->remove($user);
        $entityManager->flush();
    }

    private function removeUserByEmail(string $email): void
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user !== null) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }

    private function token(User $user): string
    {
        return static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);
    }

    private function teacherPayload(): array
    {
        return [
            'full_name' => 'Teacher Test',
            'surname' => 'Test',
            'username' => 'teacher-test',
            'email' => 'teacher@example.test',
            'password' => 'Password123',
        ];
    }
}
