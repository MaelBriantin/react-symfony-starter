<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\Auth;

use App\Application\UseCase\User\CreateUserCommand;
use App\Application\UseCase\User\CreateUserUseCase;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Infrastructure\Request\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/register', name: 'auth_register', methods: ['POST'])]
class RegisterController
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
    ) {
    }

    public function __invoke(RegisterRequest $registerRequest): JsonResponse
    {
        $command = new CreateUserCommand(
            new Email($registerRequest->email),
            new Password($registerRequest->password)
        );

        $user = $this->createUserUseCase->execute($command);

        return new JsonResponse([
            'uuid' => (string) $user->getId(),
            'email' => (string) $user->getEmail(),
        ], 201);
    }
}
