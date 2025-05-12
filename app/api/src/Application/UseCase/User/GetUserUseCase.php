<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\Data\Model\User;
use App\Domain\Port\Primary\User\GetUserUseCaseInterface;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class GetUserUseCase implements GetUserUseCaseInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function execute(Uuid $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
