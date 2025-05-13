<?php

namespace App\Infrastructure\Doctrine\Entity;

use App\Domain\Data\Model\User as UserModel;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid as UserId;
use App\Infrastructure\Doctrine\Repository\UserRepository;
use App\Infrastructure\Doctrine\Types\EmailType;
use App\Infrastructure\Doctrine\Types\PasswordType;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyString;
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

    #[ORM\Column(type: EmailType::NAME, length: 180, unique: true)]
    private Email $email;

    /** @var string[] */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: PasswordType::NAME)]
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
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        $email = (string) $this->email;
        Assert::notEmpty($email, 'Email cannot be empty');
        return $email;
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

    public function getPassword(): string
    {
        return (string) $this->password->value();
    }

    public function getPassordObject(): Password
    {
        return $this->password;
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
        $userId = new UserId($this->id->toRfc4122());
        return new UserModel(
            $userId,
            $this->email,
            $this->password,
            $this->roles,
        );
    }

    public static function fromModel(UserModel $user): self
    {
        if ($user->getPassword() === null) {
            throw new \InvalidArgumentException('Password cannot be null');
        }
        $entity = new self();
        $userId = $user->getId();
        $uuid = Uuid::fromString((string) $userId->value());
        $entity->setId($uuid);
        $entity->setEmail($user->getEmail());
        $entity->setPassword($user->getPassword());
        $entity->setRoles($user->getRoles());
        return $entity;
    }
}
