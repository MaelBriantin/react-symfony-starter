<?php

use App\Domain\Data\ValueObject\Uuid;
use InvalidArgumentException;

describe('Uuid', function () {
    it('can be created with a valid uuid', function () {
        $uuid = new Uuid('123e4567-e89b-12d3-a456-426614174000');
        expect($uuid->value())->toBe('123e4567-e89b-12d3-a456-426614174000');
    });

    it('throws if uuid is empty', function () {
        expect(fn() => new Uuid(''))
            ->toThrow(InvalidArgumentException::class, 'Uuid cannot be empty');
    });

    it('throws if uuid format is invalid', function () {
        expect(fn() => new Uuid('invalid-uuid'))
            ->toThrow(InvalidArgumentException::class, 'Invalid Uuid format');
    });

    it('throws if uuid is too short', function () {
        expect(fn() => new Uuid('123e4567-e89b-12d3-a456-426614174000123'))
            ->toThrow(InvalidArgumentException::class, 'Invalid Uuid format');
    });
});
