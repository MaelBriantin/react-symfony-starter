<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth\Login;

//use App\Domain\Port\Secondary\Auth\TokenGeneratorInterface;

readonly class LoginUseCase
{
    public function __construct(
        //        private TokenGeneratorInterface $tokenGenerator
    ) {
    }

    public function execute(LoginCommand $request): LoginResponse
    {
        //        $token = $this->tokenGenerator->generate();
        $token = 'fake-token'; // Placeholder for the token generation logic

        return new LoginResponse(
            email: $request->getUser()->getEmail()->value(),
            token: $token
        );
    }
}
