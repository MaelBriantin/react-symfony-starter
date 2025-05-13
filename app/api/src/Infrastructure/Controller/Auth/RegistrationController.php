<?php

namespace App\Infrastructure\Controller\Auth;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Infrastructure\Request\Auth\RegisterRequest;
use App\Infrastructure\Response\Auth\RegisterResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth/register', name: 'app_auth_register', methods: ['POST'])]
class RegistrationController extends AbstractController
{
    public function __construct(
        private \App\Application\UseCase\Auth\Register\RegisterUserUseCase $registerUser,
    ) {
    }

    public function __invoke(Request $request): RegisterResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid JSON data');
        }

        $registerRequest = RegisterRequest::fromArray($data);

        $command = new \App\Application\UseCase\Auth\Register\RegisterUserCommand(
            new Email($registerRequest->email),
            new Password($registerRequest->password)
        );

        $user = $this->registerUser->execute($command);

        return new RegisterResponse($user);
    }
}
