<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use SoureCode\Component\Cqrs\CommandBus;
use SoureCode\Component\Cqrs\CommandBusInterface;
use SoureCode\Component\Cqrs\EventBus;
use SoureCode\Component\Cqrs\EventBusInterface;
use SoureCode\Component\Cqrs\QueryBus;
use SoureCode\Component\Cqrs\QueryBusInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('soure_code.cqrs.event_bus', EventBus::class);

    $services
        ->alias(EventBusInterface::class, 'soure_code.cqrs.event_bus')
        ->public();

    $services->set('soure_code.cqrs.command_bus', CommandBus::class)
        ->args([
            service('soure_code.cqrs.event_bus'),
        ]);

    $services
        ->alias(CommandBusInterface::class, 'soure_code.cqrs.command_bus')
        ->public();

    $services->set('soure_code.cqrs.query_bus', QueryBus::class);

    $services
        ->alias(QueryBusInterface::class, 'soure_code.cqrs.query_bus')
        ->public();
};
