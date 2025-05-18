<?php

declare(strict_types=1);

namespace App\Domain\Exception\User;

class EmailAlreadyUsedException extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('Email "%s" already exists', $email));
    }
}
