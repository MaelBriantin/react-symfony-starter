<?php

namespace App\Application\UseCase\Auth;

use App\Application\Command\Auth\RegisterUserCommand;
use App\Domain\Data\Model\User;
use App\Domain\Port\Out\UserRepositoryPort;
use App\Domain\Port\Out\PasswordHasherPort;

class RegisterUser
{
    public function __construct(
        private UserRepositoryPort $userRepository,
        private PasswordHasherPort $passwordHasher
    ) {
    }

    public function execute(RegisterUserCommand $command): User
    {
        $user = new User(
            $command->email,
            $command->password,
            ['ROLE_USER']
        );

        $hashedPassword = $this->passwordHasher->hash($user->getPassword());
        $user->setPassword($hashedPassword);

        return $this->userRepository->save($user);
    }
}
