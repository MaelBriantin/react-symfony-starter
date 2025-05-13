<?php

namespace App\Infrastructure\Adapter;

use App\Domain\Data\Model\User as DomainUser;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use App\Infrastructure\Doctrine\Entity\User as EntityUser;

class UserAdapter
{
    public static function toDomain(EntityUser $user): DomainUser
    {
        return new DomainUser(
            id: new Uuid($user->getId()),
            email: new Email($user->getEmail()),
        );
    }
}
