<?php

namespace App\Application\UseCase\Auth\Register;

use App\Domain\Exception\User\EmailAlreadyUsedException;
use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Port\Secondary\Auth\PasswordHasherInterface;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use App\Domain\Port\Secondary\UuidGeneratorInterface;

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
        $existingUser = $this->userRepository->findOneByEmail($command->email);
        if ($existingUser !== null) {
            throw new EmailAlreadyUsedException($command->email);
        }

        $user = new User(
            new Uuid($this->uuidGenerator->generateV7()),
            $command->email,
            $command->password,
            ['ROLE_USER']
        );

        if ($user->getPassword() === null) {
            throw new \InvalidArgumentException('Password cannot be null');
        }
        $hashedPassword = $this->passwordHasher->hash($user->getPassword());
        $user->setPassword($hashedPassword);

        return $this->userRepository->save($user);
    }
}
