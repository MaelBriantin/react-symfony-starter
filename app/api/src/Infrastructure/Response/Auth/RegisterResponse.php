<?php

namespace App\Infrastructure\Response\Auth;

use App\Domain\Data\Model\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterResponse extends JsonResponse
{
    public function __construct(private User $user)
    {
        parent::__construct([
            'message' => 'User registered successfully',
            'user' => [
                'uuid' => $this->user->getId(),
                'email' => $this->user->getEmail(),
                'roles' => $this->user->getRoles(),
            ],
        ]);
    }
}
