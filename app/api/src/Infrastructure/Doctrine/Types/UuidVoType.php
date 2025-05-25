<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Data\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class UuidVoType extends Type
{
    public const string NAME = 'uuid_vo';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL(['length' => 16, 'fixed' => true]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
            if (false === $value) {
                throw new \InvalidArgumentException('Failed to read UUID resource');
            }
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('UUID value must be string or null');
        }

        if (16 === strlen($value)) {
            $uuid = SymfonyUuid::fromBinary($value)->toRfc4122();
        } else {
            $uuid = $value;
        }

        return new Uuid($uuid);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof Uuid) {
            $uuidString = $value->getValue();
        } elseif (is_string($value)) {
            $uuidString = $value;
        } else {
            throw new \InvalidArgumentException('UUID value must be Uuid object, string or null');
        }

        return SymfonyUuid::fromString($uuidString)->toBinary();
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
