<?php

namespace App\Domain\Service;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): string;
}
