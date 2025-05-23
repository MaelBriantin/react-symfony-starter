<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

final readonly class Input
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
