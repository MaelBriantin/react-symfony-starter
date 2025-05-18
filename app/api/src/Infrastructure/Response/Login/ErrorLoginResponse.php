<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\Login;

use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorLoginResponse extends JsonResponse
{
    /**
     * @param string $message
     * @param int $status
     * @param array<string, string> $headers
     */
    public function __construct(string $message, int $status = 401, array $headers = [
        'Content-Type' => 'application/json',
    ])
    {
        parent::__construct(['error' => $message], $status, $headers);
    }

    public static function credentialsMissing(): self
    {
        return new self('missing credentials');
    }

    public static function invalidCredentials(): self
    {
        return new self('invalid credentials');
    }
}
