<?php

declare(strict_types=1);

namespace Domain\Port\Primary\User;

use Domain\Data\Model\User;

interface GetAllUsersUseCaseInterface
{
    /** @return array<User> */
    public function execute(): array;
}
