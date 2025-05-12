<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\Port\Primary\User\GetAllUsersUseCaseInterface;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use App\Domain\Data\Model\User;

final class GetAllUsersUseCase implements GetAllUsersUseCaseInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /** @return array<User> */
    public function execute(): array
    {
        return $this->userRepository->findAllUsers();
    }
}
