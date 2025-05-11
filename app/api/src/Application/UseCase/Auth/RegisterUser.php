<?php

namespace App\Application\UseCase\Auth;

use App\Application\Command\RegisterUserCommand;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\Auth\PasswordHasherInterface;

class RegisterUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher
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
