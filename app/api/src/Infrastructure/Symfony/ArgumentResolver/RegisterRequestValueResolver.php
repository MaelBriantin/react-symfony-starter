<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\ArgumentResolver;

use App\Infrastructure\Request\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RegisterRequestValueResolver implements ValueResolverInterface
{
    /**
     * @return iterable<RegisterRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (RegisterRequest::class !== $argument->getType()) {
            return [];
        }

        $content = $request->getContent();
        $data = json_decode($content, true);

        if (null === $data) {
            throw new \InvalidArgumentException('Invalid JSON data');
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Expected JSON object, got '.gettype($data));
        }

        yield RegisterRequest::fromArray($data);
    }
}
