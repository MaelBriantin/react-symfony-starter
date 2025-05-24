<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\Auth;

use App\Domain\Contract\Outbound\User\UserRepositoryInterface;
use App\Domain\Data\ValueObject\Email;
use App\Infrastructure\Response\User\UserResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw $this->createAccessDeniedException('User is not authenticated');
        }

        $user = $this->userRepository->findByEmail(
            new Email($currentUser->getUserIdentifier())
        );

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->json(UserResponse::formatUser($user));
    }
}
