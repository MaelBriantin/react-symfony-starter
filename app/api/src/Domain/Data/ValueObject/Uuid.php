<?php

declare(strict_types=1);

namespace App\Domain\Data\ValueObject;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use InvalidArgumentException;

class Uuid
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
            new Assert\NotBlank(['message' => 'Uuid cannot be empty']),
            new Assert\Uuid([
                'message' => 'Invalid Uuid format',
            ]),
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
