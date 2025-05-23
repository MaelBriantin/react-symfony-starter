<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::RESPONSE, priority: 10)]
class JsonResponseListener
{
    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($response instanceof JsonResponse) {
            return;
        }

        $pathInfo = $request->getPathInfo();

        // Skip static files and assets only
        if ($this->isStaticFile($pathInfo)) {
            return;
        }

        // Process ALL other routes as API routes
        // (Since this is a pure API backend)
        $this->convertToJsonResponse($event, $response);
    }

    private function isStaticFile(string $pathInfo): bool
    {
        $staticExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.woff', '.woff2', '.ttf'];

        foreach ($staticExtensions as $extension) {
            if (str_ends_with($pathInfo, $extension)) {
                return true;
            }
        }

        return false;
    }

    private function convertToJsonResponse(ResponseEvent $event, Response $response): void
    {
        $content = $response->getContent();

        // Handle different content types
        if (empty($content)) {
            $jsonData = [];
        } else {
            // Try to decode existing JSON
            $decodedContent = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $jsonData = $decodedContent;
            } else {
                // Wrap plain text in a JSON structure
                $statusCode = $response->getStatusCode();
                $jsonData = [
                    'message' => $content,
                    'status' => $statusCode >= 400 ? 'error' : 'success'
                ];
            }
        }

        // Create new JsonResponse with proper headers
        $jsonResponse = new JsonResponse(
            $jsonData,
            $response->getStatusCode()
        );

        // Copy important headers (but override content-type)
        $headers = $response->headers->all();
        unset($headers['content-type']);

        foreach ($headers as $name => $values) {
            if (!in_array(strtolower($name), ['content-length'], true)) {
                // Filter out null values and handle proper types for PHPStan
                $filteredValues = array_filter($values, fn ($value) => $value !== null);
                if (!empty($filteredValues)) {
                    /** @var list<string> $filteredValues */
                    $jsonResponse->headers->set($name, $filteredValues);
                }
            }
        }

        // Copy cookies if any
        foreach ($response->headers->getCookies() as $cookie) {
            $jsonResponse->headers->setCookie($cookie);
        }

        $event->setResponse($jsonResponse);
    }
}
