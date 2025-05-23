<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Uuid;

class Output
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Email $email,
    ) {
    }
}
