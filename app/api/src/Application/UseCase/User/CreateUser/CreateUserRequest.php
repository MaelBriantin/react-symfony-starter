<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;

final readonly class CreateUserRequest
{
  public function __construct(
      public readonly Email $email,
      public readonly Password $password
  ) {
  }
}
