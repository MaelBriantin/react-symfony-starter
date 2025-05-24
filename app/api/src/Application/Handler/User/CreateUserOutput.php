<?php

declare(strict_types=1);

namespace App\Application\Handler\User;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Uuid;

class CreateUserOutput
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Email $email,
    ) {
    }
}
