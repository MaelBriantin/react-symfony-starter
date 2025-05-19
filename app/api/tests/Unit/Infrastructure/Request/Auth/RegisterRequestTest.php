<?php

use App\Infrastructure\Request\Auth\RegisterRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

describe('RegisterRequest', function () {
  it('creates request with valid data', function () {
    $dto = new RegisterRequest('user@email.com', 'Password1!');
    expect($dto->email)->toBe('user@email.com');
    expect($dto->password)->toBe('Password1!');
  });

  it('throws for invalid emails', function (string $email) {
    expect(fn() => new RegisterRequest($email, 'Password1!'))
      ->toThrow(BadRequestHttpException::class, 'Email is not valid');
  })->with([
    'not-an-email',
    'user@',
    '@domain.com',
    '',
  ]);

  it('throws for invalid passwords', function (string $password, string $expectedMessage) {
    expect(fn() => new RegisterRequest('user@email.com', $password))
      ->toThrow($expectedMessage);
  })->with([
    ['short', 'Password must be at least 6 characters long'],
    ['password', 'Password must contain at least one uppercase letter'],
    ['PASSWORD', 'Password must contain at least one lowercase letter'],
    ['Password', 'Password must contain at least one digit'],
    ['Password1', 'Password must contain at least one special character'],
  ]);
});
