<?php

namespace Infrastructure\Adapter;

use Domain\Data\Model\User as DomainUser;
use Domain\Data\ValueObject\Email;
use Domain\Data\ValueObject\Uuid;
use Infrastructure\Doctrine\Entity\User as EntityUser;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class UserAdapter
{
    public static function toDomain(EntityUser $user): DomainUser
    {
        $uuid = $user->getId();
        return new DomainUser(
            id: new Uuid($uuid->toRfc4122()),
            email: new Email((string) $user->getEmail()),
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
