<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\Model\User as UserModel;
use App\Domain\Port\Out\UserRepositoryPort;
use App\Infrastructure\Doctrine\Entity\User as UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<UserEntity>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryPort
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    public function save(UserModel $user): UserModel
    {
        $userEntity = UserEntity::fromModel($user);

        $this->getEntityManager()->persist($userEntity);
        $this->getEntityManager()->flush();

        return $userEntity->toModel();
    }

    public function findByEmail(string $email): ?UserModel
    {
        $entity = $this->findOneBy(['email' => $email]);

        if (!$entity) {
            return null;
        }

        return $entity->toModel();
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserEntity) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword(new Password($newHashedPassword));
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
