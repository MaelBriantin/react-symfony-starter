<?php

namespace App\Application\Command\Auth;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;

class RegisterUserCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly Password $password
    ) {
    }
}
