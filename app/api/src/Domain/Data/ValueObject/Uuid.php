<?php

declare(strict_types=1);

namespace App\Domain\Data\ValueObject;

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
        if (empty($value)) {
            throw new \InvalidArgumentException('Uuid cannot be empty');
        }

        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
            throw new \InvalidArgumentException('Invalid Uuid format');
        }
    }

    /**
     * @deprecated use getValue() instead
     */
    public function value(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
