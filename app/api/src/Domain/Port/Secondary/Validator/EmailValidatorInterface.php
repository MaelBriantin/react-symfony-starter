<?php

declare(strict_types=1);

namespace App\Domain\Port\Secondary\Validator;

use App\Domain\Data\ValueObject\Email;

interface EmailValidatorInterface
{
    public function validate(Email $email): void;
}
