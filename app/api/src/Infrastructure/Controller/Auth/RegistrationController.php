<?php

namespace App\Infrastructure\Controller\Auth;

use Webmozart\Assert\Assert;
use App\Domain\Model\User as UserModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/auth/register', name: 'app_auth_register', methods: ['POST'])]
class RegistrationController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid JSON data');
        }

        try {
            Assert::keyExists($data, 'email', 'Email is required');
            Assert::keyExists($data, 'password', 'Password is required');
            Assert::string($data['email'], 'Email must be a string');
            Assert::string($data['password'], 'Password must be a string');
            Assert::email($data['email'], 'Invalid email format');
            Assert::minLength($data['password'], 6, 'Password must be at least 6 characters long');
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $userModel = new UserModel(
            $data['email'],
            $data['password'],
            ['ROLE_USER']
        );

        $user = $userModel->toEntity();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $userModel->getPassword()
        );
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'User registered successfully',
            'user' => [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }
}
