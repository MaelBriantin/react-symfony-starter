<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Data\ValueObject\Email;
use App\Domain\Port\Secondary\User\UserRepositoryInterface;
use App\Infrastructure\Response\User\UserResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTAuthenticationSuccessHandler implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $userEvent = $event->getUser();
        $user = $this->userRepository->findByEmail(
            new Email($userEvent->getUserIdentifier())
        );
        if (null === $user) {
            throw new \RuntimeException('User not found');
        }
        $event->setData(UserResponse::formatUser($user));
    }
}
