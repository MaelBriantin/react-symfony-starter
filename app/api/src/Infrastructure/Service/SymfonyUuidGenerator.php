<?php

namespace App\Infrastructure\Service;

use App\Domain\Port\Secondary\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class SymfonyUuidGenerator implements UuidGeneratorInterface
{
    public function generateV4(): string
    {
        return Uuid::v4()->toRfc4122();
    }

    public function generateV7(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
