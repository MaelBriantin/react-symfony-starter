<?php

namespace App\Application\Command;

class RegisterUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
