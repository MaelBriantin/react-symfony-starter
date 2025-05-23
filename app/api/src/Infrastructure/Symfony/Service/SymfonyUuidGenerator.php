<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Service;

use App\Domain\Contract\Outbound\UuidGeneratorInterface;
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
