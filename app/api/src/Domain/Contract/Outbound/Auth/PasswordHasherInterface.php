<?php

declare(strict_types=1);

namespace App\Domain\Contract\Outbound\Auth;

use App\Domain\Data\ValueObject\Password;

interface PasswordHasherInterface
{
    public function hash(Password $plainPassword): Password;
}
