<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Data\Model\User as DomainUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private readonly DomainUser $domainUser
    ) {
    }

    public function getUserIdentifier(): string
    {
        $email = $this->domainUser->getEmail()->getValue();

        if ('' === $email) {
            throw new \RuntimeException('User email cannot be empty');
        }

        return $email;
    }

    public function getRoles(): array
    {
        return $this->domainUser->getRoles();
    }

    public function getPassword(): string
    {
        return $this->domainUser->getPassword()->getValue();
    }

    public function eraseCredentials(): void
    {
        // Nothing to erase as we use value objects
    }

    public function getDomainUser(): DomainUser
    {
        return $this->domainUser;
    }
}
