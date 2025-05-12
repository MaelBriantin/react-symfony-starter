<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\Auth;

use App\Domain\Data\Model\User;
use App\Infrastructure\Response\User\AbstractUserResponse;

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
