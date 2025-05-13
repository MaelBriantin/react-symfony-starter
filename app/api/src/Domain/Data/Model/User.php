<?php

namespace App\Domain\Data\Model;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use Webmozart\Assert\Assert;

class User
{
    /**
     * @param array<string> $roles
     */
    public function __construct(
        private Uuid $id,
        private Email $email,
        private Password $password,
        private array $roles = []
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUniqueIdentifier(): Email
    {
        return $this->email;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function setPassword(Password $password): self
    {
        $this->password = $password;
        return $this;
    }
}
