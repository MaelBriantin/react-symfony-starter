<?php

namespace App\Infrastructure\Doctrine\Entity;

use App\Domain\Data\Model\User as UserModel;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Infrastructure\Doctrine\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'email', length: 180, unique: true)]
    private Email $email;

    /** @var string[] */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'password')]
    private Password $password;

    public function __construct()
    {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): Email
    {
        Assert::stringNotEmpty($this->email, 'Email cannot be empty');
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        Assert::stringNotEmpty($this->email, 'Email cannot be empty');
        return (string) $this->email;
    }

    /** @return string[] */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /** @param string[] $roles */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPasswordObject(): Password
    {
        return $this->password;
    }

    public function getPassword(): string
    {
        return $this->password->value();
    }

    public function setPassword(Password $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function toModel(): UserModel
    {
        return new UserModel(
            $this->email,
            $this->password,
            $this->roles,
            $this->id
        );
    }

    public static function fromModel(UserModel $user): self
    {
        $entity = new self();
        $entity->setId($user->getIdObject());
        $entity->setEmail($user->getEmailObject());
        $entity->setPassword($user->getPassword());
        $entity->setRoles($user->getRoles());
        return $entity;
    }
}
