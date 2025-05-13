<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Data\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
    public const string NAME = 'email_vo';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        return $value !== null ? new Email((string) $value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Email ? (string) $value->value() : ($value !== null ? (string) $value : null);
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
