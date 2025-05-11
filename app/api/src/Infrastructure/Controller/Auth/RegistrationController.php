<?php

namespace App\Infrastructure\Controller\Auth;

use App\Application\Command\RegisterUserCommand;
use App\Application\UseCase\Auth\RegisterUser;
use App\Infrastructure\Request\Auth\RegisterRequest;
use App\Infrastructure\Response\Auth\RegisterResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[Route('/auth/register', name: 'app_auth_register', methods: ['POST'])]
class RegistrationController extends AbstractController
{
    public function __construct(
        private RegisterUser $registerUser,
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
            $registerRequest->email,
            $registerRequest->password
        );

        $user = $this->registerUser->execute($command);

        return new RegisterResponse($user);
    }
}
