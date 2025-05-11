<?php

namespace Tests\Unit\Domain\Data\ValueObject;

use App\Domain\Data\ValueObject\Password;
use InvalidArgumentException;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PasswordTest extends TestCase
{
    public function test_valid_password_can_be_created(): void
    {
        $password = new Password('Password123!');
        
        $this->assertSame('Password123!', $password->value());
    }

    public function test_password_too_short_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters long');

        new Password('Pass123');
    }

    public function test_password_without_uppercase_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter');

        new Password('password123!');
    }

    public function test_password_without_lowercase_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one lowercase letter');

        new Password('PASSWORD123');
    }

    public function test_password_without_number_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one number');

        new Password('PasswordABC');
    }

    public function test_password_without_special_character_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one special character');

        new Password('Password123');
    }

    #[DataProvider('validPasswordProvider')]
    public function test_various_valid_passwords_are_accepted(string $validPassword): void
    {
        $password = new Password($validPassword);
        $this->assertSame($validPassword, $password->value());
    }

    public static function validPasswordProvider(): array
    {
        return [
            'minimum requirements' => ['Password1!'],
            'complex password' => ['MyC0mpl3x!P@ssw0rd'],
            'with special chars' => ['P@ssw0rd!'],
            'only required chars' => ['P@ssw0rd'],
        ];
    }
}
