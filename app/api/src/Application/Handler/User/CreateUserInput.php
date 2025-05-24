<?php

declare(strict_types=1);

namespace App\Application\Handler\User;

final readonly class CreateUserInput
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
