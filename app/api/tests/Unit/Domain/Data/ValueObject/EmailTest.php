<?php

use App\Domain\Data\ValueObject\Email;

test('email can be created with valid format', function () {
    $email = new Email('test@example.com');
    expect($email->value())->toBe('test@example.com');
});

test('email cannot be created with invalid format', function () {
    expect(fn () => new Email('invalid-email'))
        ->toThrow(\InvalidArgumentException::class, 'Invalid email format');
});

test('email cannot be created with empty string', function () {
    expect(fn () => new Email(''))
        ->toThrow(\InvalidArgumentException::class, 'Invalid email format');
});
