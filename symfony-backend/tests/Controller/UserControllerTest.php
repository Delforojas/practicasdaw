<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserControllerTest extends TestCase
{
    public function testRegisterRejectsUnsupportedImageMimeType(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'profile-upload-');
        file_put_contents($path, 'not an image');
        $image = new UploadedFile($path, 'profile.txt', 'text/plain', UPLOAD_ERR_OK, true);

        try {
            $response = $this->controller()->register(
                $this->request($image),
                $this->entityManager(),
                $this->passwordHasher()
            );

            self::assertSame(415, $response->getStatusCode());
        } finally {
            unlink($path);
        }
    }

    public function testRegisterRejectsImagesLargerThanFiveMiB(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'profile-upload-');
        file_put_contents($path, str_repeat('x', 5 * 1024 * 1024 + 1));
        $image = new UploadedFile($path, 'profile.jpg', 'image/jpeg', UPLOAD_ERR_OK, true);

        try {
            $response = $this->controller()->register(
                $this->request($image),
                $this->entityManager(),
                $this->passwordHasher()
            );

            self::assertSame(413, $response->getStatusCode());
        } finally {
            unlink($path);
        }
    }

    private function controller(): UserController
    {
        $controller = new UserController();
        $controller->setContainer(new Container());

        return $controller;
    }

    private function request(UploadedFile $image): Request
    {
        return Request::create(
            '/api/register',
            'POST',
            [
                'email' => 'user@example.test',
                'password' => 'Password123',
                'full_name' => 'Test User',
                'username' => 'test-user',
            ],
            [],
            ['profileImage' => $image]
        );
    }

    private function entityManager(): EntityManagerInterface
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('getRepository');

        return $entityManager;
    }

    private function passwordHasher(): UserPasswordHasherInterface
    {
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects(self::never())->method('hashPassword');

        return $passwordHasher;
    }
}
