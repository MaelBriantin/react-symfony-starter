<?php

declare(strict_types=1);

namespace App\Infrastructure\Response\User;

use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractUserResponse extends JsonResponse
{
    /**
     * @param User|array<User> $users
     * @param array<string, mixed> $additionalData
     */
    public function __construct(
        protected User|array $users,
        protected string $message,
        array $additionalData = []
    ) {
        $users = is_array($this->users) ? $this->users : [$this->users];

        $data = [
            'message' => $this->message,
            'users' => array_map(
                /**
                 * @return array{uuid: string, email: string, roles: array<string>}
                 */
                static fn (User $user): array => self::formatUser($user),
                $users
            ),
        ];

        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }

        parent::__construct($data);
    }

    /**
     * @return array{uuid: string, email: string, roles: array<string>}
     */
    protected static function formatUser(User $user): array
    {
        return [
            'uuid' => (string) $user->getId(),
            'email' => (string) $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }
}
