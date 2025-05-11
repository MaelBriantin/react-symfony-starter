<?php

namespace Tests\Unit\Domain\Data\ValueObject;

use App\Domain\Data\ValueObject\Email;
use InvalidArgumentException;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class EmailTest extends TestCase
{
    public function test_valid_email_can_be_created(): void
    {
        $email = new Email('john.doe@example.com');
        
        $this->assertSame('john.doe@example.com', $email->value());
        $this->assertSame('john.doe@example.com', (string) $email);
    }

    public function test_empty_email_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        new Email('');
    }

    public function test_invalid_email_format_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        new Email('invalid-email');
    }

    #[DataProvider('invalidEmailProvider')]
    public function test_various_invalid_emails_throw_exception(string $invalidEmail): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email($invalidEmail);
    }

    public static function invalidEmailProvider(): array
    {
        return [
            'missing @' => ['johndoe.com'],
            'missing domain' => ['john@'],
            'missing local part' => ['@example.com'],
            'invalid characters' => ['john<>doe@example.com'],
            'multiple @' => ['john@doe@example.com'],
            'spaces' => ['john doe@example.com'],
        ];
    }
}
