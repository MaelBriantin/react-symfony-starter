<?php

namespace App\Infrastructure\Controller\Auth;

// ...

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(): JsonResponse
    {
        // ... e.g. get the user data from a registration form
        $user = new User();
        $plaintextPassword = 'plaintext_password';

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        return new JsonResponse([
            'message' => 'User registered successfully',
            'user' => [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }
}
