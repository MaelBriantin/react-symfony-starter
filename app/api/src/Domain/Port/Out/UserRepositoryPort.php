<?php

namespace App\Domain\Port\Out;

use App\Domain\Data\Model\User;

interface UserRepositoryPort
{
    public function save(User $user): User;
}
