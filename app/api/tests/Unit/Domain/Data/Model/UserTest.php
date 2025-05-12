<?php

namespace Tests\Unit\Domain\Data\Model;

use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use \App\Domain\Data\ValueObject\Uuid;
use App\Infrastructure\Service\SymfonyUuidGenerator;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_creation_with_valid_data(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $roles = ['ROLE_ADMIN'];
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, $roles);

        $this->assertInstanceOf(Uuid::class, $user->getId());
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            (string) $user->getId()->value()
        );
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($password, $user->getPassword());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function test_user_always_has_role_user(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);

        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertCount(1, $user->getRoles());
    }

    public function test_user_roles_are_unique(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $roles = ['ROLE_USER', 'ROLE_USER', 'ROLE_ADMIN', 'ROLE_ADMIN'];
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, $roles);

        $this->assertCount(2, $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function test_user_password_can_be_updated(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $newPassword = new Password('NewPassword123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);
        $user->setPassword($newPassword);

        $this->assertSame($newPassword, $user->getPassword());
    }

    public function test_user_email_can_be_updated(): void
    {
        $email = new Email('john@example.com');
        $newEmail = new Email('john.doe@example.com');
        $password = new Password('Password123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);
        $user->setEmail($newEmail);

        $this->assertSame($newEmail, $user->getEmail());
        $this->assertSame('john.doe@example.com', (string) $user->getEmail());
    }

    public function test_user_roles_can_be_updated(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEqualsCanonicalizing(
            ['ROLE_USER', 'ROLE_ADMIN'],
            $user->getRoles()
        );
    }
}
