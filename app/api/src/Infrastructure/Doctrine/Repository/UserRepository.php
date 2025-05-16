<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\Model\User as UserModel;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Uuid;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use App\Infrastructure\Adapter\UserAdapter;
use App\Infrastructure\Doctrine\Entity\User as UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

/**
 * @extends ServiceEntityRepository<UserEntity>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly UserAdapter $userAdapter
    ) {
        parent::__construct($this->registry, UserEntity::class);
    }

    public function save(UserModel $user): UserModel
    {
        $userEntity = $this->userAdapter->toEntity($user);

        $this->getEntityManager()->persist($userEntity);
        $this->getEntityManager()->flush();

        return $this->userAdapter->toDomain($userEntity);
    }

    public function findByEmail(Email $email): ?UserModel
    {
        $entity = $this->findOneBy(['email' => $email]);

        if (!$entity) {
            return null;
        }

        return $this->userAdapter->toDomain($entity);
    }

    public function findById(Uuid $id): ?UserModel
    {
        $entity = $this->find(SymfonyUuid::fromString($id->value()));

        if (!$entity) {
            return null;
        }

        return $this->userAdapter->toDomain($entity);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserEntity) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword(new Password($newHashedPassword, true));
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /** @return array<UserEntity> */
    protected function findAllEntities(): array
    {
        return parent::findAll();
    }

    /** @return array<UserModel> */
    public function findAllUsers(): array
    {
        $entities = $this->findAllEntities();

        return array_map(
            fn (UserEntity $entity): UserModel => $this->userAdapter->toDomain($entity),
            $entities
        );
    }
}
