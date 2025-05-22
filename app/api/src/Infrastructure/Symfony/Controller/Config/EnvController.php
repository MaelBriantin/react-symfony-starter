<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\Config;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class EnvController extends AbstractController
{
    #[Route('/config/env', name: 'config_env', methods: ['GET'])]
    public function getEnv(): JsonResponse
    {
        $env = [
            'apiUrl' => $_ENV['API_URL'] ?? null,
            'clientUrl' => $_ENV['CLIENT_URL'] ?? null,
        ];
        return $this->json($env);
    }
}
