<?php

declare(strict_types=1);

namespace Domain\Port\Secondary\Auth;

use Domain\Data\ValueObject\Password;

interface PasswordHasherInterface
{
    public function hash(Password $plainPassword): Password;
}
