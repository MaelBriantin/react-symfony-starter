<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Data\Model\User as DomainUser;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use App\Infrastructure\Doctrine\Entity\User as EntityUser;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class UserAdapter
{
    public static function toDomain(EntityUser $user): DomainUser
    {
        $uuid = $user->getId();
        $password = $user->getPassword();
        if (null === $password) {
            throw new \InvalidArgumentException('User password cannot be null.');
        }
        return new DomainUser(
            id: new Uuid($uuid->toRfc4122()),
            email: new Email((string) $user->getEmail()),
            password: new Password((string) $user->getPassword()),
        );
    }

    public static function toEntity(DomainUser $user): EntityUser
    {
        $entity = new EntityUser();

        $entity->setId(SymfonyUuid::fromString($user->getId()->value()));
        $entity->setEmail($user->getEmail());
        $entity->setRoles($user->getRoles());

        $userPassword = $user->getPassword();
        if ($userPassword !== null) {
            $entity->setPassword($userPassword);
        }

        return $entity;
    }
}
