<?php

namespace App\Application\UseCase\Auth;

use App\Application\Command\Auth\RegisterUserCommand;
use App\Domain\Port\Secondary\UuidGeneratorInterface;
use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use App\Domain\Port\Secondary\Auth\PasswordHasherInterface;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function execute(RegisterUserCommand $command): User
    {
        $user = new User(
            new Uuid($this->uuidGenerator->generateV7()),
            $command->email,
            $command->password,
            ['ROLE_USER']
        );

        $hashedPassword = $this->passwordHasher->hash($user->getPassword());
        $user->setPassword($hashedPassword);

        return $this->userRepository->save($user);
    }
}
