<?php

declare(strict_types=1);

namespace Application\UseCase\User;

use Domain\Data\Model\User;
use Domain\Port\Primary\User\GetUserUseCaseInterface;
use Domain\Port\Secondary\User\UserRepositoryInterface;
use Domain\Data\ValueObject\Uuid;

class GetUserUseCase implements GetUserUseCaseInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(Uuid $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
