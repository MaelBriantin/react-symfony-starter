<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Auth\PasswordHasherInterface;
use App\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function hash(string $plainPassword): string
    {
        $user = new User();
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}
