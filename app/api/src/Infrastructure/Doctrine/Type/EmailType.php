<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Data\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
    public const NAME = 'email';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Email ? $value->value() : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if (!is_string($value) || empty($value)) {
            return null;
        }

        return new Email($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
