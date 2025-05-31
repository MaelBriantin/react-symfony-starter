<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Factory;

use App\Domain\Contract\Outbound\Factory\UserFactoryInterface;
use App\Domain\Contract\Outbound\Security\PasswordHasherInterface;
use App\Domain\Contract\Outbound\Service\UuidGeneratorInterface;
use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;

final class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function createUser(string $email = 'user@example.com', string $password = 'UserPassword123!'): User
    {
        return $this->createWithRoles($email, $password, ['ROLE_USER']);
    }

    public function createWithRoles(string $email, string $password, array $roles): User
    {
        $emailVO = new Email($email);
        $passwordVO = new Password($password);
        $uuid = new Uuid($this->uuidGenerator->generateV7());

        $user = new User(
            $uuid,
            $emailVO,
            $passwordVO,
            $roles
        );

        $hashedPassword = $this->passwordHasher->hash($passwordVO);
        $user->setPassword($hashedPassword);

        return $user;
    }

    public function createMultiple(int $count, string $baseEmail = 'user', string $basePassword = 'Password123!'): array
    {
        $users = [];

        for ($i = 1; $i <= $count; $i++) {
            $email = "{$baseEmail}{$i}@example.com";
            $password = str_replace('123!', "{$i}123!", $basePassword);
            $users[] = $this->createUser($email, $password);
        }

        return $users;
    }
}
