<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth\Login;

use App\Domain\Data\Model\User;

readonly class LoginCommand
{
    public function __construct(
        private User $user
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
