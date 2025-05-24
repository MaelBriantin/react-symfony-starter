<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;

readonly class CreateUserCommand
{
    public function __construct(
        public Email $email,
        public Password $password
    ) {
    }
}
