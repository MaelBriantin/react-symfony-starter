<?php

namespace App\Domain\Data\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class Password
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
            new Assert\Length(['min' => 8, 'minMessage' => 'Password must be at least 8 characters long']),
            new Assert\Regex([
                'pattern' => '/[A-Z]/',
                'message' => 'Password must contain at least one uppercase letter',
            ]),
            new Assert\Regex([
                'pattern' => '/[a-z]/',
                'message' => 'Password must contain at least one lowercase letter',
            ]),
            new Assert\Regex([
                'pattern' => '/[0-9]/',
                'message' => 'Password must contain at least one number',
            ]),
            new Assert\Regex([
                'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
                'message' => 'Password must contain at least one special character',
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
