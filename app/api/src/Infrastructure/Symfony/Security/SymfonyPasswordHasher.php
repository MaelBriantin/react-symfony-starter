<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Service;

use App\Domain\Contract\Outbound\Security\PasswordHasherInterface;
use App\Domain\Data\ValueObject\Password;
use App\Infrastructure\Security\SecurityUser;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

readonly class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    public function hash(Password $plainPassword): Password
    {
        $hasher = $this->passwordHasherFactory->getPasswordHasher(SecurityUser::class);
        $hashedPassword = $hasher->hash($plainPassword->getValue());

        return new Password($hashedPassword);
    }
}
