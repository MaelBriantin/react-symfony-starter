<?php

namespace App\Domain\Data\Model;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;
use App\Infrastructure\Doctrine\Entity\User as UserEntity;

class User
{
    private Uuid $id;
    private Email $email;
    /** @var array<string> */
    private array $roles = [];
    private Password $password;

    /**
     * @param array<string> $roles
     */
    public function __construct(
        Email $email,
        Password $password,
        array $roles = [],
        ?Uuid $id = null
    ) {
        $this->id = $id ?? Uuid::v7();
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getIdObject(): Uuid
    {
        return $this->id;
    }

    public function getId(): string
    {
        return $this->id->toRfc4122();
    }

    public function getEmailObject(): Email
    {
        return $this->email;
    }

    public function getEmail(): string
    {
        return $this->email->value();
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
            $user->getPasswordObject(),
            $user->getRoles(),
            $user->getId()
        );
    }
}
