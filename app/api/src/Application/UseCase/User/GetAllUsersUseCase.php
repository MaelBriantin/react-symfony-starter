<?php

declare(strict_types=1);

namespace Application\UseCase\User;

use Domain\Port\Primary\User\GetAllUsersUseCaseInterface;
use Domain\Port\Secondary\User\UserRepositoryInterface;
use Domain\Data\Model\User;

final class GetAllUsersUseCase implements GetAllUsersUseCaseInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /** @return array<User> */
    public function execute(): array
    {
        return $this->userRepository->findAllUsers();
    }
}
