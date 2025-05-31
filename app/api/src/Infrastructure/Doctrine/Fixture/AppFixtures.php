<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixture;

use App\Domain\Contract\Outbound\Factory\UserFactoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserFactoryInterface $userFactory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $testUser = $this->userFactory->createUser(
            email: 'test@example.com',
            password: 'Password123!',
        );

        $manager->persist($testUser);
        $manager->flush();
    }
}
