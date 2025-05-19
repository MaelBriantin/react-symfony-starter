<?php

namespace Application\UseCase\Auth\Register;

use Domain\Data\ValueObject\Email;
use Domain\Data\ValueObject\Password;

class RegisterUserCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly Password $password
    ) {}
}
