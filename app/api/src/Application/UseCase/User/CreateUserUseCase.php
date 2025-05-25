<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\Contract\Outbound\Security\PasswordHasherInterface;
use App\Domain\Contract\Outbound\User\UserRepositoryInterface;
use App\Domain\Contract\Outbound\Service\UuidGeneratorInterface;
use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Exception\UserAlreadyExistsException;

final class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function execute(CreateUserCommand $command): User
    {
        $existingUser = $this->userRepository->findByEmail($command->email);
        if (null !== $existingUser) {
            throw new UserAlreadyExistsException((string) $command->email);
        }

        $user = new User(
            new Uuid($this->uuidGenerator->generateV7()),
            $command->email,
            $command->password,
            ['ROLE_USER']
        );

        $hashedPassword = $this->passwordHasher->hash($command->password);
        $user->setPassword($hashedPassword);

        return $this->userRepository->save($user);
    }
}
