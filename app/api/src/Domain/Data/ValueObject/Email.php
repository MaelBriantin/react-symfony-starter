<?php

declare(strict_types=1);

namespace App\Domain\Data\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($value, [
            new Assert\NotBlank(['message' => 'Email cannot be empty']),
            new Assert\Email(['message' => 'Invalid email format'])
        ]);
        if (count($violations) > 0) {
            $violation = $violations->get(0);
            throw new InvalidArgumentException((string) $violation->getMessage());
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
