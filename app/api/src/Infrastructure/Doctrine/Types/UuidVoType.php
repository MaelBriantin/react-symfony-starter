<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Data\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UuidVoType extends Type
{
  public const string NAME = 'uuid_vo';

  public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
  {
    return $platform->getBinaryTypeDeclarationSQL(['length' => 16, 'fixed' => true]);
  }

  public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
  {
    if ($value === null || $value === '') {
      return null;
    }

    if (is_resource($value)) {
      $value = stream_get_contents($value);
    }

    if (!is_string($value)) {
      throw new \InvalidArgumentException('UUID value must be string or null');
    }

    // Convert binary to string UUID
    if (strlen($value) === 16) {
      // Binary format - convert to hex string then format as UUID
      $hex = bin2hex($value);
      $uuid = sprintf(
        '%08s-%04s-%04s-%04s-%012s',
        substr($hex, 0, 8),
        substr($hex, 8, 4),
        substr($hex, 12, 4),
        substr($hex, 16, 4),
        substr($hex, 20, 12)
      );
    } else {
      // Assume it's already a properly formatted UUID string
      $uuid = $value;
    }

    return new Uuid($uuid);
  }

  public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
  {
    if ($value === null) {
      return null;
    }

    if ($value instanceof Uuid) {
      $uuidString = $value->value();
    } elseif (is_string($value)) {
      $uuidString = $value;
    } else {
      throw new \InvalidArgumentException('UUID value must be Uuid object, string or null');
    }

    // Remove hyphens and convert to binary
    $hex = str_replace('-', '', $uuidString);
    return hex2bin($hex);
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
