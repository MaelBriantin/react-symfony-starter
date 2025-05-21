<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\User;

use App\Domain\Data\Model\User;

class UserResponse extends AbstractUserResponse
{
    public function __construct(User $user)
    {
        parent::__construct(
            users: $user,
            message: 'User details'
        );
    }

    public static function formatUser(User $user): array
    {
        return [
            'uuid' => (string) $user->getId(),
            'email' => (string) $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }
}
