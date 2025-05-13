<?php

namespace App\Infrastructure\Controller\Auth;

use App\Infrastructure\Doctrine\Entity\User as EntityUser;
use App\Infrastructure\Service\SymfonyUuidGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?EntityUser $entityUser): JsonResponse
    {
        if (null === $entityUser) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $domainUser = $entityUser->toModel();

        // TODO: Generate a real token
        $token = new SymfonyUuidGenerator()->generateV4();

        return $this->json([
            'user' => $domainUser->getEmail()->value(),
            'token' => $token,
        ]);
    }
}
