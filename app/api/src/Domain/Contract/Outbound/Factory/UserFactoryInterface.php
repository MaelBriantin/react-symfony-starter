<?php

declare(strict_types=1);

namespace App\Domain\Contract\Outbound\Factory;

use App\Domain\Data\Model\User;

interface UserFactoryInterface
{
    public function createUser(string $email = 'user@example.com', string $password = 'UserPassword123!'): User;

    /**
     * @param array<string> $roles
     */
    public function createWithRoles(string $email, string $password, array $roles): User;

    /**
     * @return array<User>
     */
    public function createMultiple(int $count, string $baseEmail = 'user', string $basePassword = 'Password123!'): array;
}
