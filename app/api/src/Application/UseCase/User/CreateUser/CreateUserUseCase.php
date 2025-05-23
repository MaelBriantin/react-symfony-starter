<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

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

    public function execute(CreateUserRequest $request): CreateUserResponse
    {
        $existingUser = $this->userRepository->findByEmail($request->email);
        if ($existingUser !== null) {
            throw new \InvalidArgumentException('Email already exists.');
        }

        $user = new User(
            new Uuid($this->uuidGenerator->generateV7()),
            $request->email,
            $request->password,
            ['ROLE_USER']
        );

        if ($user->getPassword() === null) {
            throw new \InvalidArgumentException('Password cannot be null');
        }
        $hashedPassword = $this->passwordHasher->hash($request->password);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);

        return new CreateUserResponse(
            $user->getId(),
            $user->getEmail(),
            $user->getPassword()
        );
    }
}
