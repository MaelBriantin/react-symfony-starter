<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Port\Secondary\Auth\PasswordHasherInterface;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use App\Domain\Port\Secondary\UuidGeneratorInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function handle(Input $input): Output
    {
        $email = new Email($input->email);
        $password = new Password($input->password);
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser !== null) {
            throw new UserAlreadyExistsException((string) $email);
        }

        $user = new User(
            new Uuid($this->uuidGenerator->generateV7()),
            $email,
            $password,
            ['ROLE_USER']
        );

        if ($user->getPassword() === null) {
            throw new \InvalidArgumentException('Password cannot be null');
        }
        $hashedPassword = $this->passwordHasher->hash($password);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);

        return new Output(
            $user->getId(),
            $user->getEmail()
        );
    }
}
