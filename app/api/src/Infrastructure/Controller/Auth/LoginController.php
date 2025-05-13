<?php

namespace App\Infrastructure\Controller\Auth;

use App\Application\UseCase\Auth\Login\LoginUseCase;
use App\Infrastructure\Adapter\UserAdapter;
use App\Infrastructure\Doctrine\Entity\User as EntityUser;
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
    public function index(#[CurrentUser] ?EntityUser $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $domainUser = UserAdapter::toDomain($user);

        $response = $this->loginUseCase->execute(
            new \App\Application\UseCase\Auth\Login\LoginCommand($domainUser)
        );

        return $this->json([
            'user' => $response->getEmail(),
            'token' => $response->getToken(),
        ]);
    }
}
