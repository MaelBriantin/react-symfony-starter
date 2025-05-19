<?php

namespace Application\UseCase\Auth\Register;

use Domain\Data\Model\User;
use Domain\Data\ValueObject\Uuid;
use Domain\Port\Secondary\Auth\PasswordHasherInterface;
use Domain\Port\Secondary\User\UserRepositoryInterface;
use Domain\Port\Secondary\UuidGeneratorInterface;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private UuidGeneratorInterface $uuidGenerator
    ) {}

    public function execute(RegisterUserCommand $command): User
    {
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
