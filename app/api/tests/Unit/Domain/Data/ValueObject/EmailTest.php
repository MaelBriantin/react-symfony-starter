<?php

use Domain\Data\ValueObject\Email;
use InvalidArgumentException;

describe('Email', function () {
    it('can be created with a valid email', function () {
        $email = new Email('john.doe@example.com');
        expect($email->value())->toBe('john.doe@example.com');
        expect((string) $email)->toBe('john.doe@example.com');
    });

    it('throws if email is empty', function () {
        expect(fn() => new Email(''))
            ->toThrow(InvalidArgumentException::class, 'Email cannot be empty');
    });

    it('throws if email format is invalid', function () {
        expect(fn() => new Email('invalid-email'))
            ->toThrow(InvalidArgumentException::class, 'Invalid email format');
    });

    it('throws for various invalid emails', function (string $invalidEmail) {
        expect(fn() => new Email($invalidEmail))
            ->toThrow(InvalidArgumentException::class);
    })->with([
        'johndoe.com',
        'john@',
        '@example.com',
        'john<>doe@example.com',
        'john@doe@example.com',
        'john doe@example.com',
    ]);
});
