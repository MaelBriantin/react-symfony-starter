<?php

declare(strict_types=1);

namespace App\Domain\Port\Primary\User;

use App\Domain\Data\Model\User;
use Symfony\Component\Uid\Uuid;

interface GetUserUseCaseInterface
{
    public function execute(Uuid $id): ?User;
}
