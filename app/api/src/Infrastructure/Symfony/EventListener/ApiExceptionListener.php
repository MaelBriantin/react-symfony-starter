<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener(event: 'kernel.exception')]
class ApiExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
        $message = $exception->getMessage() ?: 'Internal Server Error';

        $response = new JsonResponse(
            ['error' => $message],
            $statusCode,
            ['Content-Type' => 'application/json']
        );

        $event->setResponse($response);
    }
}
