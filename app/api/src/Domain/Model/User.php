<?php

namespace App\Domain\Model;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;
use App\Infrastructure\Entity\User as UserEntity;

class User
{
    private Uuid $id;
    private string $email;
    /** @var array<string> */
    private array $roles = [];
    private string $password;

    /**
     * @param array<string> $roles
     */
    public function __construct(
        string $email,
        string $password,
        array $roles = []
    ) {
        $this->id = Uuid::v7();
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        Assert::stringNotEmpty($this->email, 'Email cannot be empty');
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function toEntity(): UserEntity
    {
        $user = new UserEntity();
        $user->setEmail($this->email);
        $user->setPassword($this->password);
        $user->setRoles($this->roles);

        return $user;
    }

    public static function fromEntity(UserEntity $user): self
    {
        return new self(
            $user->getEmail(),
            $user->getPassword(),
            $user->getRoles()
        );
    }
}
