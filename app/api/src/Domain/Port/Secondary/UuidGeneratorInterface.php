<?php

declare(strict_types=1);

namespace Domain\Port\Secondary;

interface UuidGeneratorInterface
{
    public function generateV4(): string;

    public function generateV7(): string;
}
