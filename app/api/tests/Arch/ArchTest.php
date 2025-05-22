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
    ->not->toUse('App\Infrastructure\**');

arch()
    ->expect('App\Infrastructure\**')
    ->not->toUse('App\Application\**');

arch()
    ->expect('App\Application\**')
    ->not->toUse('App\Infrastructure\**');

