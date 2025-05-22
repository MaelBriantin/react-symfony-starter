<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\Auth;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Infrastructure\Request\Auth\RegisterRequest;
use App\Infrastructure\Response\Auth\RegisterResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\UseCase\Auth\Register\RegisterUserUseCase;
use App\Application\UseCase\Auth\Register\RegisterUserCommand;

#[Route('/auth/register', name: 'auth_register', methods: ['POST'])]
class RegisterController extends AbstractController
{
    public function __construct(
        private RegisterUserUseCase $registerUser,
    ) {
    }

    public function __invoke(Request $request): RegisterResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid JSON data');
        }
        $registerRequest = RegisterRequest::fromArray($data);
        $command = new RegisterUserCommand(
            new Email($registerRequest->email),
            new Password($registerRequest->password)
        );
        $user = $this->registerUser->execute($command);
        return new RegisterResponse($user);
    }
}
