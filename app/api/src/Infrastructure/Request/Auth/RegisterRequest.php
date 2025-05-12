<?php

namespace App\Infrastructure\Request\Auth;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmozart\Assert\Assert;

class RegisterRequest
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        try {
            Assert::email($this->email, 'L\'email n\'est pas valide');
            Assert::minLength($this->password, 6, 'Le mot de passe doit faire au moins 6 caractères');
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
            Assert::keyExists($data, 'email', 'Le champ email est requis');
            Assert::keyExists($data, 'password', 'Le champ password est requis');
            Assert::string($data['email'], 'L\'email doit être une chaîne de caractères');
            Assert::string($data['password'], 'Le mot de passe doit être une chaîne de caractères');

            return new self(
                email: $data['email'],
                password: $data['password'],
            );
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
