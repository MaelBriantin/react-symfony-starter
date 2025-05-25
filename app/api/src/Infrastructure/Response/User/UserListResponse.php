<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\User;

use App\Domain\Data\Model\User;

final class UserListResponse extends AbstractUserResponse
{
    /**
     * @param array<User> $users
     */
    public function __construct(array $users)
    {
        parent::__construct(
            users: $users,
            message: 'Users list',
            // For example purposes only
            additionalData: [
                'count' => count($users),
            ]
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
