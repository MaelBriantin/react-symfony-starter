<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth/check', name: 'auth_check', methods: ['GET'])]
class CheckAuthController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
        ]);
    }
}