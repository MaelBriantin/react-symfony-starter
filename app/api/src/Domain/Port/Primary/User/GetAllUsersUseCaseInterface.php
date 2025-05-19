<?php

declare(strict_types=1);

namespace App\Domain\Port\Primary\User;

use App\Domain\Data\Model\User;

interface GetAllUsersUseCaseInterface
{
    /** @return array<User> */
    public function execute(): array;
}
