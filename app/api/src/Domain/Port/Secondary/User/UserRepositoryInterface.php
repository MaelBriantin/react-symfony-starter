<?php

declare(strict_types=1);

namespace Domain\Port\Secondary\User;

use Domain\Data\Model\User;
use Domain\Data\ValueObject\Email;
use Domain\Data\ValueObject\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): User;
    public function findByEmail(Email $email): ?User;

    /** @return array<User> */
    public function findAllUsers(): array;

    public function findById(Uuid $id): ?User;
}
