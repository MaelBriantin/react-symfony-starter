<?php

declare(strict_types=1);

namespace App\Domain\Contract\Inbound\User;

use App\Domain\Data\Model\User;

interface GetAllUsersUseCaseInterface
{
    /** @return array<User> */
    public function execute(): array;
}
