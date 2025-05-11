<?php

namespace App\Domain\Service\Auth;

use App\Domain\Data\ValueObject\Password;

interface PasswordHasherInterface
{
    public function hash(Password $plainPassword): Password;
}
