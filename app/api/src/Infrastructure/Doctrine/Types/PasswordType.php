<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Data\ValueObject\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PasswordType extends StringType
{
    public const string NAME = 'password_vo';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Password value must be string or null');
        }

        // When converting from DB, the password is HASHED
        return new Password(
            value: $value,
            isHashed: true
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Password) {
            return $value->value();
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Password value must be Password object, string or null');
        }

        return $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
