<?php

declare(strict_types=1);

namespace App\Domain\Contract\Outbound\Service;

interface UuidGeneratorInterface
{
    public function generateV4(): string;

    public function generateV7(): string;
}
