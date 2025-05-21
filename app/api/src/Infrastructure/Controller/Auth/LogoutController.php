<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Auth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogoutController extends AbstractController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->logout();
    }

    /**
     * @return Response
     */
    public function logout()
    {
        $response = new Response();
        $response->headers->clearCookie('BEARER');

        $response->setContent(json_encode(['message' => 'Successfully logged out']));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}