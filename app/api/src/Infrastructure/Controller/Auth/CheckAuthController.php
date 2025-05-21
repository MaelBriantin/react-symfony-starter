<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Auth;

use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Domain\Data\ValueObject\Email;
use App\Infrastructure\Response\User\UserResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth/check', name: 'auth_check', methods: ['GET'])]
class CheckAuthController extends AbstractController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->userRepository->findByEmail(
            new Email($this->getUser()->getUserIdentifier())
        );
        return $this->json(UserResponse::formatUser($user));
    }
}
