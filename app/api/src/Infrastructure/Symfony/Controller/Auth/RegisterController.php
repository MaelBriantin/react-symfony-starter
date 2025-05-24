<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\Auth;

use App\Application\Handler\User\CreateUserHandler as Handler;
use App\Application\Handler\User\CreateUserInput as Input;
use App\Infrastructure\Request\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/register', name: 'auth_register', methods: ['POST'])]
class RegisterController
{
    public function __construct(
        private Handler $handler,
    ) {
    }

    public function __invoke(RegisterRequest $registerRequest): JsonResponse
    {
        $input = new Input(
            $registerRequest->email,
            $registerRequest->password
        );

        $output = $this->handler->handle($input);

        return new JsonResponse([
            'uuid' => (string) $output->id,
            'email' => (string) $output->email,
        ], 201);
    }
}
