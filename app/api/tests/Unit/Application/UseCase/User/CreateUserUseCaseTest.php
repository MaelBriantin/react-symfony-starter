<?php

use App\Application\UseCase\User\CreateUserCommand;
use App\Application\UseCase\User\CreateUserUseCase;
use App\Domain\Contract\Outbound\Security\PasswordHasherInterface;
use App\Domain\Contract\Outbound\User\UserRepositoryInterface;
use App\Domain\Contract\Outbound\Service\UuidGeneratorInterface;
use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Exception\UserAlreadyExistsException;

describe('CreateUserUseCase', function () {
  beforeEach(function () {
    $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
    $this->uuidGenerator = $this->createMock(UuidGeneratorInterface::class);

    $this->useCase = new CreateUserUseCase(
      $this->userRepository,
      $this->passwordHasher,
      $this->uuidGenerator
    );
  });

  it('creates a new user successfully', function () {
    $email = new Email('test@example.com');
    $password = new Password('Password123!');
    $hashedPassword = new Password('$2y$10$hashedPassword');
    $uuid = '01970402-a323-7797-9097-29d166abbd03';

    $command = new CreateUserCommand($email, $password);

    $this->userRepository
      ->expects($this->once())
      ->method('findByEmail')
      ->with($email)
      ->willReturn(null);

    $this->uuidGenerator
      ->expects($this->once())
      ->method('generateV7')
      ->willReturn($uuid);

    $this->passwordHasher
      ->expects($this->once())
      ->method('hash')
      ->with($password)
      ->willReturn($hashedPassword);

    $expectedUser = new User(
      new Uuid($uuid),
      $email,
      $hashedPassword,
      ['ROLE_USER']
    );

    $this->userRepository
      ->expects($this->once())
      ->method('save')
      ->with($this->callback(function ($user) use ($email, $hashedPassword, $uuid) {
        return $user instanceof User
          && $user->getEmail()->getValue() === $email->getValue()
          && $user->getPassword()->getValue() === $hashedPassword->getValue()
          && $user->getId()->value() === $uuid
          && $user->getRoles() === ['ROLE_USER'];
      }))
      ->willReturn($expectedUser);

    $result = $this->useCase->execute($command);

    expect($result)->toBeInstanceOf(User::class);
    expect($result->getEmail()->getValue())->toBe('test@example.com');
    expect($result->getPassword()->getValue())->toBe('$2y$10$hashedPassword');
    expect($result->getId()->value())->toBe($uuid);
    expect($result->getRoles())->toContain('ROLE_USER');
  });

  it('throws exception when user already exists', function () {
    $email = new Email('existing@example.com');
    $password = new Password('Password123!');
    $command = new CreateUserCommand($email, $password);

    $existingUser = new User(
      new Uuid('01970402-a323-7797-9097-29d166abbd03'),
      $email,
      new Password('$2y$10$hashedPassword123!'),
      ['ROLE_USER']
    );

    $this->userRepository
      ->expects($this->once())
      ->method('findByEmail')
      ->with($email)
      ->willReturn($existingUser);

    $this->uuidGenerator
      ->expects($this->never())
      ->method('generateV7');

    $this->passwordHasher
      ->expects($this->never())
      ->method('hash');

    $this->userRepository
      ->expects($this->never())
      ->method('save');

    expect(fn() => $this->useCase->execute($command))
      ->toThrow(UserAlreadyExistsException::class, "User with email 'existing@example.com' already exists");
  });

  it('creates user with default ROLE_USER', function () {
    $email = new Email('newuser@example.com');
    $password = new Password('Password123!');
    $hashedPassword = new Password('$2y$10$hashedPassword');
    $uuid = '01970402-a323-7797-9097-29d166abbd03';

    $command = new CreateUserCommand($email, $password);

    $this->userRepository
      ->expects($this->once())
      ->method('findByEmail')
      ->with($email)
      ->willReturn(null);

    $this->uuidGenerator
      ->expects($this->once())
      ->method('generateV7')
      ->willReturn($uuid);

    $this->passwordHasher
      ->expects($this->once())
      ->method('hash')
      ->with($password)
      ->willReturn($hashedPassword);

    $this->userRepository
      ->expects($this->once())
      ->method('save')
      ->with($this->callback(function ($user) {
        return $user instanceof User && $user->getRoles() === ['ROLE_USER'];
      }))
      ->willReturnArgument(0);

    $result = $this->useCase->execute($command);

    expect($result->getRoles())->toBe(['ROLE_USER']);
  });

  it('hashes password before saving', function () {
    $email = new Email('test@example.com');
    $plainPassword = new Password('PlainPassword123!');
    $hashedPassword = new Password('$2y$10$hashedPasswordValue');
    $uuid = '01970402-a323-7797-9097-29d166abbd03';

    $command = new CreateUserCommand($email, $plainPassword);

    $this->userRepository
      ->expects($this->once())
      ->method('findByEmail')
      ->with($email)
      ->willReturn(null);

    $this->uuidGenerator
      ->expects($this->once())
      ->method('generateV7')
      ->willReturn($uuid);

    $this->passwordHasher
      ->expects($this->once())
      ->method('hash')
      ->with($plainPassword)
      ->willReturn($hashedPassword);

    $this->userRepository
      ->expects($this->once())
      ->method('save')
      ->with($this->callback(function ($user) use ($hashedPassword) {
        return $user instanceof User
          && $user->getPassword()->getValue() === $hashedPassword->getValue();
      }))
      ->willReturnArgument(0);

    $result = $this->useCase->execute($command);

    expect($result->getPassword()->getValue())->toBe('$2y$10$hashedPasswordValue');
    expect($result->getPassword()->getValue())->not->toBe('PlainPassword123!');
  });
});
