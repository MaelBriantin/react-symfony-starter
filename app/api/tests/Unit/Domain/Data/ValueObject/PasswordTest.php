<?php

use App\Domain\Data\ValueObject\Password;

test('password can be created with valid value', function () {
    $password = new Password('ValidP@ss123');
    expect($password->value())->toBe('ValidP@ss123');
});

test('password requires minimum length of 8 characters', function () {
    expect(fn () => new Password('Short1'))->toThrow(
        InvalidArgumentException::class,
        'Password must be at least 8 characters long'
    );
});

test('password requires at least one uppercase letter', function () {
    expect(fn () => new Password('password123'))->toThrow(
        InvalidArgumentException::class,
        'Password must contain at least one uppercase letter'
    );
});

test('password requires at least one lowercase letter', function () {
    expect(fn () => new Password('PASSWORD123'))->toThrow(
        InvalidArgumentException::class,
        'Password must contain at least one lowercase letter'
    );
});

test('password requires at least one number', function () {
    expect(fn () => new Password('PasswordTest'))->toThrow(
        InvalidArgumentException::class,
        'Password must contain at least one number'
    );
});
