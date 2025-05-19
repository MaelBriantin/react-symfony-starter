<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\User;

use App\Domain\Data\Model\User;

class IndexResponse extends AbstractUserResponse
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
}
