<?php

declare(strict_types=1);

namespace Infrastructure\Response\User;

use Domain\Data\Model\User;

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
