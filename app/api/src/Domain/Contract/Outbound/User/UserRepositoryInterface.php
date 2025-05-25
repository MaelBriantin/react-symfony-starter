<?php

declare(strict_types=1);

namespace App\Domain\Contract\Outbound\User;

use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function findByEmail(Email $email): ?User;

    /** @return array<User> */
    public function findAllUsers(): array;

    public function findById(Uuid $id): ?User;
}
