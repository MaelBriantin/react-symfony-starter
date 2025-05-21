<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Auth;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LogoutController extends AbstractController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['GET'])]
    public function __invoke(): Response
    {
        $response = new Response();

        $cookie = Cookie::create('BEARER')
          ->withValue('')
          ->withExpires(time() - 3600)
          ->withPath('/')
          ->withDomain(null)
          ->withSecure(true)
          ->withHttpOnly(true)
          ->withSameSite(Cookie::SAMESITE_NONE)
          ->withPartitioned(true);

        $response->headers->setCookie($cookie);
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent((string) json_encode(['message' => 'Successfully logged out'], JSON_THROW_ON_ERROR));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
