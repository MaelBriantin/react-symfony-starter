<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Contract\Outbound\User\UserRepositoryInterface;
use App\Domain\Data\Model\User as UserModel;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @implements ObjectRepository<UserModel>
 */
class UserRepository implements PasswordUpgraderInterface, UserRepositoryInterface, ObjectRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function save(UserModel $user): UserModel
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function findByEmail(Email $email): ?UserModel
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u')
            ->from(UserModel::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result instanceof UserModel ? $result : null;
    }

    public function findById(Uuid $id): ?UserModel
    {
        return $this->entityManager->find(UserModel::class, $id);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserModel) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword(new Password($newHashedPassword, true));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /** @return array<UserModel> */
    public function findAllUsers(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u')
            ->from(UserModel::class, 'u');

        /** @var array<UserModel> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    // ObjectRepository methods
    public function find($id): ?UserModel
    {
        return $this->entityManager->find(UserModel::class, $id);
    }

    public function findAll(): array
    {
        return $this->findAllUsers();
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->entityManager->getRepository(UserModel::class)->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria): ?UserModel
    {
        return $this->entityManager->getRepository(UserModel::class)->findOneBy($criteria);
    }

    public function getClassName(): string
    {
        return UserModel::class;
    }
}
