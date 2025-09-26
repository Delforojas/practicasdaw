<?php


namespace App\Controller;
use App\Entity\User;
use App\Entity\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;




class UserController extends AbstractController
    {
            #[Route('/api/register', name: 'user_register', methods: ['POST'])]
            public function register(
                Request $request,
                EntityManagerInterface $entityManager,
                UserPasswordHasherInterface $passwordHasher
            ): JsonResponse {

                $fullName   = $request->request->get('full_name');
                $surname    = $request->request->get('surname');
                $username   = $request->request->get('username');
                $phone      = $request->request->get('phone');
                $address    = $request->request->get('address');
                $postalCode = $request->request->get('postal_code');
                $city       = $request->request->get('city');
                $email      = $request->request->get('email');
                $password   = $request->request->get('password');
                /** @var UploadedFile $imageFile */
                $imageFile = $request->files->get('profileImage');


                if (!$email || !$password || !$fullName || !$username) {
                    return new JsonResponse(['error' => 'Missing required fields.'], Response::HTTP_BAD_REQUEST);
                }


                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($existingUser) {
                    return new JsonResponse(['error' => 'Ya existe una cuenta registrada con este correo electrónico. Intenta con otro o inicia sesión.'], Response::HTTP_CONFLICT);
                }
                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
                if ($existingUser) {
                    return new JsonResponse(['error' => 'Ya existe un usuario con este nombre.Prueba con otro.'], Response::HTTP_CONFLICT);
                }



                $user = new User();

                $user->setFullName($fullName);
                $user->setSurName($surname);
                $user->setUserName($username);
                $user->setPhone($phone);
                $user->setAddress($address);
                $user->setPostalCode($postalCode);
                $user->setCity($city);
                $user->setEmail($email);
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $user->setRole(RoleEnum::USER);
                $user->setCreatedAt(new \DateTimeImmutable());

                 if ($imageFile) {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('uploads_directory'), // definido en services.yaml
                        $newFilename
                    );
                    $user->setProfileImage($newFilename);
                }


                $entityManager->persist($user);
                $entityManager->flush();


                return new JsonResponse(['message' => 'User registered successfully.'], Response::HTTP_CREATED);
            }

            #[Route('/api/teacher', name: 'user_teacher', methods: ['POST'])]
            #[IsGranted('ROLE_ADMIN')] 
            public function teacher(
                Request $request,
                EntityManagerInterface $entityManager,
                UserPasswordHasherInterface $passwordHasher
                ): JsonResponse {
                $data = json_decode($request->getContent(), true);


                $fullName     = $data['full_name'] ?? null; // 👈 mapeo a name
                $surname    = $data['surname'] ?? null;
                $username   = $data['username'] ?? null;
                $phone      = $data['phone'] ?? null;
                $address    = $data['address'] ?? null;
                $postalCode = $data['postal_code'] ?? null;
                $city       = $data['city'] ?? null;
                $email      = $data['email'] ?? null;
                $password   = $data['password'] ?? null;


                if (!$email || !$password || !$fullName || !$username) {
                    return new JsonResponse(['error' => 'Missing required fields.'], Response::HTTP_BAD_REQUEST);
                }


                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($existingUser) {
                    return new JsonResponse(['error' => 'Ya existe una cuenta registrada con este correo electrónico. Intenta con otro o inicia sesión.'], Response::HTTP_CONFLICT);
                }
                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
                if ($existingUser) {
                    return new JsonResponse(['error' => 'Ya existe un usuario con este nombre.Prueba con otro.'], Response::HTTP_CONFLICT);
                }
                $user = new User();

                $user->setFullName($fullName);
                $user->setSurName($surname);
                $user->setUserName($username);
                $user->setPhone($phone);
                $user->setAddress($address);
                $user->setPostalCode($postalCode);
                $user->setCity($city);
                $user->setEmail($email);
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $user->setRole(RoleEnum::TEACHER);
                $user->setCreatedAt(new \DateTimeImmutable());

                $entityManager->persist($user);
                $entityManager->flush();

                return new JsonResponse(['message' => 'User Teacher registered successfully.'], Response::HTTP_CREATED);
            }


                #[Route('/api/me', name: 'user_me', methods: ['GET'])]
                public function me(UserInterface $user , Request $request): JsonResponse
                {
                    return new JsonResponse([

                        'id'          => $user->getId(),
                        'full_name'   => $user->getFullName(),
                        'surname'     => $user->getSurName(),
                        'username'    => $user->getUserName(),
                        'email'       => $user->getEmail(),
                        'phone'       => $user->getPhone(),
                        'address'     => $user->getAddress(),
                        'postal_code' => $user->getPostalCode(),
                        'city'        => $user->getCity(),
                        'role'        => $user->getRole()->value,      // enum -> string
                        //'roles'       => $user->getRoles(),        Array de roles
                        'created_at'  => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
                        'profileImage' => $user->getProfileImage()
                            ? $request->getSchemeAndHttpHost().'/uploads/'.$user->getProfileImage()
                            : null,

                    ]);
                }

    }
