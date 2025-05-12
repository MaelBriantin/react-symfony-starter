<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\User;

use App\Domain\Data\Model\User;

class ShowResponse extends AbstractUserResponse
{
    public function __construct(User $user)
    {
        parent::__construct(
            users: $user,
            message: 'User details'
        );
    }
}
