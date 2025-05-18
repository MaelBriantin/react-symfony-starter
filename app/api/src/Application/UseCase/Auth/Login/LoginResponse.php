<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth\Login;

readonly class LoginResponse
{
    public function __construct(
        private string $email,
        private string $token
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
