<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;

class CreateUserResponse
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Email $email,
        public readonly Password $password,
    ) {
    }
}