<?php

namespace App\Infrastructure\Controller\Auth;

use App\Application\UseCase\Auth\Login\LoginCommand;
use App\Application\UseCase\Auth\Login\LoginUseCase;
use App\Infrastructure\Adapter\UserAdapter;
use App\Infrastructure\Doctrine\Entity\User as EntityUser;
use App\Infrastructure\Response\Login\ErrorLoginResponse;
use App\Infrastructure\Response\Login\SuccessLoginResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase
    ) {
    }

    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?EntityUser $user): SuccessLoginResponse | JsonResponse
    {
        if (null === $user) {
            return ErrorLoginResponse::invalidCredentials();
        }

        $domainUser = UserAdapter::toDomain($user);

        $response = $this->loginUseCase->execute(
            new LoginCommand($domainUser)
        );

        return new SuccessLoginResponse($response);
    }
}
