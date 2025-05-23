<?php

use App\Domain\Data\Model\User;
use App\Domain\Data\ValueObject\Email;
use App\Domain\Data\ValueObject\Password;
use App\Domain\Data\ValueObject\Uuid;
use App\Infrastructure\Symfony\Service\SymfonyUuidGenerator;

describe('User', function () {
    it('can be created with valid data', function () {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $roles = ['ROLE_ADMIN'];
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, $roles);

        expect($user->getId())->toBeInstanceOf(Uuid::class);
        expect((string) $user->getId()->value())
            ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i');
        expect($user->getEmail())->toBe($email);
        expect($user->getPassword())->toBe($password);
        expect($user->getRoles())->toContain('ROLE_USER');
        expect($user->getRoles())->toContain('ROLE_ADMIN');
    });

    it('always has ROLE_USER', function () {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);

        expect($user->getRoles())->toContain('ROLE_USER');
        expect($user->getRoles())->toHaveCount(1);
    });

    it('roles are unique', function () {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $roles = ['ROLE_USER', 'ROLE_USER', 'ROLE_ADMIN', 'ROLE_ADMIN'];
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, $roles);

        expect($user->getRoles())->toHaveCount(2);
        expect($user->getRoles())->toContain('ROLE_USER');
        expect($user->getRoles())->toContain('ROLE_ADMIN');
    });

    it('password can be updated', function () {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $newPassword = new Password('NewPassword123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);
        $user->setPassword($newPassword);

        expect($user->getPassword())->toBe($newPassword);
    });

    it('email can be updated', function () {
        $email = new Email('john@example.com');
        $newEmail = new Email('john.doe@example.com');
        $password = new Password('Password123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);
        $user->setEmail($newEmail);

        expect($user->getEmail())->toBe($newEmail);
        expect((string) $user->getEmail())->toBe('john.doe@example.com');
    });

    it('roles can be updated', function () {
        $email = new Email('john@example.com');
        $password = new Password('Password123!');
        $uuidGenerator = new SymfonyUuidGenerator();
        $user = new User(new Uuid($uuidGenerator->generateV7()), $email, $password, []);
        $user->setRoles(['ROLE_ADMIN']);

        expect($user->getRoles())
            ->toEqualCanonicalizing(['ROLE_USER', 'ROLE_ADMIN']);
    });
});
