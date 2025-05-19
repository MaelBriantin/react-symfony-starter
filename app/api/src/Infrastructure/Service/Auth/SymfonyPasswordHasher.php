<?php

namespace Infrastructure\Service\Auth;

use Domain\Data\ValueObject\Password;
use Domain\Port\Secondary\Auth\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Infrastructure\Doctrine\Entity\User;

readonly class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function hash(Password $plainPassword): Password
    {
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plainPassword->value()
        );

        return new Password($hashedPassword);
    }
}
