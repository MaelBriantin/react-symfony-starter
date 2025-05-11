<?php

namespace App\Infrastructure\Validator\Auth;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmozart\Assert\Assert;

class RegisterRequestValidator
{
    public function validate(array $data): void
    {
        try {
            Assert::keyExists($data, 'email', 'Email is required');
            Assert::keyExists($data, 'password', 'Password is required');
            Assert::string($data['email'], 'Email must be a string');
            Assert::string($data['password'], 'Password must be a string');
            Assert::email($data['email'], 'Invalid email format');
            Assert::minLength($data['password'], 6, 'Password must be at least 6 characters long');
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
