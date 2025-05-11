<?php

namespace App\Domain\Data\ValueObject;

use InvalidArgumentException;

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
        if (strlen($value) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long');
        }

        if (!preg_match('/[A-Z]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter');
        }

        if (!preg_match('/[a-z]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one lowercase letter');
        }

        if (!preg_match('/[0-9]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one number');
        }

        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one special character');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
