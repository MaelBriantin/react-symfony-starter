<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth\Register;

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
