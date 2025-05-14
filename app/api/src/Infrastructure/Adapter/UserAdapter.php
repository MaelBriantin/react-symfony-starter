<?php

namespace App\Infrastructure\Adapter;

use App\Domain\Data\Model\User as DomainUser;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Uuid;
use App\Infrastructure\Doctrine\Entity\User as EntityUser;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class UserAdapter
{
    public static function toDomain(EntityUser $user): DomainUser
    {
        return new DomainUser(
            id: new Uuid($user->getId()),
            email: new Email($user->getEmail()),
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
