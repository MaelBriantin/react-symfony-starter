<?php

namespace App\Infrastructure\Response\Auth;

use App\Domain\Model\User;

class RegisterResponse implements \JsonSerializable
{
    public function __construct(private User $user)
    {}

    public function jsonSerialize(): array
    {
        return [
            'message' => 'User registered successfully',
            'user' => [
                'email' => $this->user->getEmail(),
                'roles' => $this->user->getRoles(),
            ],
        ];
    }
}