<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\Auth;

use App\Domain\Data\Model\User;
use App\Infrastructure\Response\User\AbstractUserResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessRegisterResponse extends JsonResponse
{
    public function __construct(User $user)
    {
        parent::__construct([
            'message' => 'User registered successfully',
            'user' => [
                'uuid' => (string) $user->getId(),
                'email' => (string) $user->getEmail(),
            ]
        ]);
    }
}
