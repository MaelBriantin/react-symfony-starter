<?php

namespace App\Domain\Port\Out;

use App\Domain\Data\ValueObject\Password;

interface PasswordHasherPort
{
    public function hash(Password $plainPassword): Password;
}
