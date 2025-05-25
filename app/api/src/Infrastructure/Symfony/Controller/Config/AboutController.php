<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\Config;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AboutController extends AbstractController
{
    #[Route('/')]
    public function about(): Response
    {
        return $this->json([
            'api' => 'React-Symfony-Starter API',
            'symfony_version' => \Symfony\Component\HttpKernel\Kernel::VERSION,
            'php_version' => phpversion(),
        ]);
    }
}
