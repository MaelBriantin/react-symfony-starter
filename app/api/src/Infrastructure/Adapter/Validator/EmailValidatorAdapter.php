<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter\Validator;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Port\Secondary\Validator\EmailValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidator;

class EmailValidatorAdapter implements EmailValidatorInterface
{
    public function __construct(private SymfonyValidator $validator)
    {
    }

    public function validate(Email $email): void
    {
        $violations = $this->validator->validate($email);
        if (count($violations) > 0) {
            throw new ValidatorException((string) $violations);
        }
    }
}
