<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Auth;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmozart\Assert\Assert;

final class RegisterRequest
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
        $this->validate();
    }

    private function validateEmail(): void
    {
        try {
            Assert::email($this->email, 'Email is not valid');
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    private function validatePassword(): void
    {
        try {
            Assert::minLength($this->password, 6, 'Password must be at least 6 characters long');
            Assert::regex($this->password, '/[A-Z]/', 'Password must contain at least one uppercase letter');
            Assert::regex($this->password, '/[a-z]/', 'Password must contain at least one lowercase letter');
            Assert::regex($this->password, '/\d/', 'Password must contain at least one digit');
            Assert::regex($this->password, '/[^A-Za-z0-9]/', 'Password must contain at least one special character');
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    private function validate(): void
    {
        try {
            $this->validateEmail();
            $this->validatePassword();
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * @param array<mixed, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        try {
            Assert::keyExists($data, 'email', 'Email is required');
            Assert::keyExists($data, 'password', 'Password is required');
            Assert::string($data['email'], 'Email must be a string');
            Assert::string($data['password'], 'Password must be a string');

            return new self(
                email: $data['email'],
                password: $data['password'],
            );
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
