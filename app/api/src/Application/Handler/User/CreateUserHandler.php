<?php

declare(strict_types=1);

namespace App\Application\Handler\User;

use App\Application\Handler\User\CreateUserInput as Input;
use App\Application\Handler\User\CreateUserOutput as Output;
use App\Domain\Contract\Outbound\Security\PasswordHasherInterface;
use App\Domain\Contract\Outbound\User\UserRepositoryInterface;
use App\Domain\Contract\Outbound\UuidGeneratorInterface;
use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Exception\UserAlreadyExistsException;

final class CreateUserHandler
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
        if (null !== $existingUser) {
            throw new UserAlreadyExistsException((string) $email);
        }

        $user = new User(
            new Uuid($this->uuidGenerator->generateV7()),
            $email,
            $password,
            ['ROLE_USER']
        );

        $hashedPassword = $this->passwordHasher->hash($password);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);

        return new Output(
            $user->getId(),
            $user->getEmail()
        );
    }
}
