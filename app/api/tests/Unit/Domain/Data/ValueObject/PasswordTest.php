<?php

use Domain\Data\ValueObject\Password;
use InvalidArgumentException;

describe('Password', function () {
    it('can be created with a valid password', function () {
        $password = new Password('Password123!');
        expect($password->value())->toBe('Password123!');
    });

    it('throws if password is too short', function () {
        expect(fn() => new Password('Pass123'))
            ->toThrow(InvalidArgumentException::class, 'Password must be at least 8 characters long');
    });

    it('throws if password has no uppercase', function () {
        expect(fn() => new Password('password123!'))
            ->toThrow(InvalidArgumentException::class, 'Password must contain at least one uppercase letter');
    });

    it('throws if password has no lowercase', function () {
        expect(fn() => new Password('PASSWORD123'))
            ->toThrow(InvalidArgumentException::class, 'Password must contain at least one lowercase letter');
    });

    it('throws if password has no number', function () {
        expect(fn() => new Password('PasswordABC'))
            ->toThrow(InvalidArgumentException::class, 'Password must contain at least one number');
    });

    it('throws if password has no special character', function () {
        expect(fn() => new Password('Password123'))
            ->toThrow(InvalidArgumentException::class, 'Password must contain at least one special character');
    });

    it('accepts various valid passwords', function (string $validPassword) {
        $password = new Password($validPassword);
        expect($password->value())->toBe($validPassword);
    })->with([
        'Password1!',
        'MyC0mpl3x!P@ssw0rd',
        'P@ssw0rd!',
        'P@ssw0rd',
    ]);
});
