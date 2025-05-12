<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Data\ValueObject;

use App\Domain\Data\ValueObject\Uuid;
use InvalidArgumentException;
use Tests\TestCase;

class UuidTest extends TestCase
{
    public function test_valid_uuid_can_be_created(): void
    {
        $uuid = new Uuid('123e4567-e89b-12d3-a456-426614174000');
        
        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', $uuid->value());
    }

    public function test_empty_uuid_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Uuid cannot be empty');

        new Uuid('');
    }

    public function test_invalid_uuid_format_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Uuid format');

        new Uuid('invalid-uuid');
    }

    public function test_uuid_too_short_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Uuid format');

        new Uuid('123e4567-e89b-12d3-a456-426614174000123');
    }
}