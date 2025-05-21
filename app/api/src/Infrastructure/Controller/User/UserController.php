<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User;

use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Port\Primary\User\GetUserUseCaseInterface;
use App\Domain\Port\Primary\User\GetAllUsersUseCaseInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Infrastructure\Response\User\UserResponse;
use App\Infrastructure\Response\User\UserListResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends AbstractController
{
    public function __construct(
        private GetAllUsersUseCaseInterface $getAllUsers,
        private GetUserUseCaseInterface $getUser,
    ) {
    }

    #[Route('/users', name: 'user_index', methods: ['GET'])]
    public function index(): UserListResponse
    {
        $users = $this->getAllUsers->execute();

        return new UserListResponse($users);
    }

    #[Route('/users/{id}', name: 'user_show', methods: ['GET'])]
    public function show(string $id): UserResponse
    {
        try {
            $uuid = new Uuid($id);
            $user = $this->getUser->execute($uuid);

            if (!$user) {
                throw new NotFoundHttpException('User not found');
            }

            return new UserResponse($user);
        } catch (\InvalidArgumentException $e) {
            throw new NotFoundHttpException('Invalid UUID format');
        }
    }
}
