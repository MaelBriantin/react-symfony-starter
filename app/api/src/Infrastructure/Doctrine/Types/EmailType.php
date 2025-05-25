<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Data\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
    public const string NAME = 'email_vo';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Email value must be string or null');
        }

        return new Email($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof Email) {
            return $value->value();
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Invalid email value type');
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
