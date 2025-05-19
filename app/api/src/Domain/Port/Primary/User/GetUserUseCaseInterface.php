<?php

declare(strict_types=1);

namespace Domain\Port\Primary\User;

use Domain\Data\Model\User;
use Domain\Data\ValueObject\Uuid;

interface GetUserUseCaseInterface
{
    public function execute(Uuid $id): ?User;
}
