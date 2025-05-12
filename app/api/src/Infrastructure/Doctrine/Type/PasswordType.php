<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Data\ValueObject\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class PasswordType extends Type
{
    public const NAME = 'password';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('Password value must be a string');
        }

        return new Password($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Password) {
            throw new InvalidArgumentException('Value must be an instance of Password');
        }

        return $value->value();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
