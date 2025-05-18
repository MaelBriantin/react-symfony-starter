<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\Login;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\UseCase\Auth\Login\LoginResponse;

class SuccessLoginResponse extends JsonResponse
{
    /**
     * @param LoginResponse $loginResponse
     * @param int $status
     * @param array<string, string> $headers
     */
    public function __construct(LoginResponse $loginResponse, int $status = 200, array $headers = [])
    {
        $data = [
            'user' => $loginResponse->getEmail(),
            'token' => $loginResponse->getToken(),
        ];
        parent::__construct($data, $status, $headers);
    }
}
