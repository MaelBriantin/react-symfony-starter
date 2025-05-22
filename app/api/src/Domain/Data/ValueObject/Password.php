<?php

declare(strict_types=1);

namespace App\Domain\Data\ValueObject;

use InvalidArgumentException;

class Password
{
    private string $value;

    public function __construct(
        string $value,
        bool $isHashed = false
    ) {
        if (!$isHashed) {
            $this->validate($value);
        }

        $this->value = $value;
    }

    public function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Password cannot be empty');
        }

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

        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one special character');
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
