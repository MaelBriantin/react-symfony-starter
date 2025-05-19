<?php

declare(strict_types=1);

namespace Infrastructure\Response\Auth;

use Domain\Data\Model\User;
use Infrastructure\Response\User\AbstractUserResponse;

class RegisterResponse extends AbstractUserResponse
{
    public function __construct(User $user)
    {
        parent::__construct(
            users: $user,
            message: 'User registered successfully'
        );
    }
}
