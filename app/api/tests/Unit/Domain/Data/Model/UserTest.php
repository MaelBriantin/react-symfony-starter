<?php

namespace Tests\Unit\Domain\Data\Model;

use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use Symfony\Component\Uid\Uuid;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_creation_with_valid_data(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $roles = ['ROLE_ADMIN'];

        $user = new User($email, $password, $roles);

        $this->assertInstanceOf(Uuid::class, $user->getIdObject());
        $this->assertSame($email, $user->getEmailObject());
        $this->assertSame($password, $user->getPassword());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function test_user_always_has_role_user(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        
        $user = new User($email, $password);

        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertCount(1, $user->getRoles());
    }

    public function test_user_roles_are_unique(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $roles = ['ROLE_USER', 'ROLE_USER', 'ROLE_ADMIN', 'ROLE_ADMIN'];

        $user = new User($email, $password, $roles);

        $this->assertCount(2, $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function test_user_password_can_be_updated(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $newPassword = new Password('NewPassword123!');

        $user = new User($email, $password);
        $user->setPassword($newPassword);

        $this->assertSame($newPassword, $user->getPassword());
    }

    public function test_user_email_can_be_updated(): void
    {
        $email = new Email('john@example.com');
        $newEmail = new Email('john.doe@example.com');
        $password = new Password('Password123!');

        $user = new User($email, $password);
        $user->setEmail($newEmail);

        $this->assertSame($newEmail, $user->getEmailObject());
        $this->assertSame('john.doe@example.com', $user->getEmail());
    }

    public function test_user_roles_can_be_updated(): void
    {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        
        $user = new User($email, $password);
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEqualsCanonicalizing(
            ['ROLE_USER', 'ROLE_ADMIN'],
            $user->getRoles()
        );
    }
}
