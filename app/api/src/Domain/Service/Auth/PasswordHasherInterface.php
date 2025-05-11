<?php

namespace App\Domain\Service\Auth;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): string;
}
