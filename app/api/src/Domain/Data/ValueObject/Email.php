<?php

declare(strict_types=1);

namespace Domain\Data\ValueObject;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(
        string $value,
    ) {
        $this->validate($value);
        $this->value = $value;
    }

    public function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
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
