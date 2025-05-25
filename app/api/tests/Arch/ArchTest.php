<?php

arch()
    ->expect('App')
    ->toUseStrictTypes()
    ->not->toUse(['die', 'dd', 'dump']);

arch()
    ->expect('App\Domain\Data\Enum')
    ->toBeEnums();

arch()
    ->expect('App\Domain\**')
    ->not->toUse([
        'App\Application\**',
        'App\Infrastructure\**'
    ]);

arch()
    ->expect('App\Application\**')
    ->not->toUse('App\Infrastructure\**');

arch('Controllers can use Application layer')
    ->expect('App\Infrastructure\Symfony\Controller\**')
    ->toUse('App\Application\**');

arch('UserRepository must implement UserRepositoryInterface')
    ->expect('App\Infrastructure\Doctrine\Repository\UserRepository')
    ->toImplement('App\Domain\Contract\Outbound\User\UserRepositoryInterface');

arch('Domain contracts should only use Domain layer')
    ->expect('App\Domain\Contract')
    ->not->toUse([
        'App\Application\**',
        'App\Infrastructure\**'
    ]);

arch('Inbound contracts should be interfaces')
    ->expect('App\Domain\Contract\Inbound')
    ->classes()
    ->toBeInterfaces();

arch('Outbound contracts should be interfaces')
    ->expect('App\Domain\Contract\Outbound')
    ->classes()
    ->toBeInterfaces();

arch('Event Listeners should be in Infrastructure Symfony layer')
    ->expect('App\Infrastructure\Symfony\EventListener')
    ->classes()
    ->toHaveSuffix('Listener');
    

arch('UseCase classes should be final')
    ->expect('App\Application\UseCase\**')
    ->classes()
    ->toBeFinal();

arch('Request classes should be final')
    ->expect('App\Infrastructure\Request\**')
    ->classes()
    ->toBeFinal();

arch('Controllers should be final')
    ->expect('App\Infrastructure\Symfony\Controller\**')
    ->classes()
    ->toBeFinal();

arch('Application DTOs should be final')
    ->expect([
        'App\Application\UseCase\User\CreateUserCommand',
        'App\Application\Handler\User\CreateUserInput',
        'App\Application\Handler\User\CreateUserOutput'
    ])
    ->toBeFinal();

arch('Response concrete classes should be final')
    ->expect([
        'App\Infrastructure\Response\Auth\RegisterResponse',
        'App\Infrastructure\Response\User\UserResponse', 
        'App\Infrastructure\Response\User\UserListResponse'
    ])
    ->toBeFinal();
