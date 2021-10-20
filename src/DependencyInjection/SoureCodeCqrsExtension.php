<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\DependencyInjection;

use SoureCode\Component\Cqrs\CommandHandlerInterface;
use SoureCode\Component\Cqrs\EventHandlerInterface;
use SoureCode\Component\Cqrs\QueryHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class SoureCodeCqrsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        /**
         * @var array{
         *      event_bus: array{message_bus_id: string},
         *      command_bus: array{message_bus_id: string},
         *      query_bus: array{message_bus_id: string}
         * } $config
         */
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        $loader->load('services.php');

        $eventBusDefinition = $container->getDefinition('soure_code.cqrs.event_bus');
        $eventBusDefinition->addArgument(new Reference($config['event_bus']['message_bus_id']));

        $commandBusDefinition = $container->getDefinition('soure_code.cqrs.command_bus');
        $commandBusDefinition->setArgument('$messageBus', new Reference($config['command_bus']['message_bus_id']));

        $queryBusDefinition = $container->getDefinition('soure_code.cqrs.query_bus');
        $queryBusDefinition->addArgument(new Reference($config['query_bus']['message_bus_id']));

        $container->registerForAutoconfiguration(EventHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => $config['event_bus']['message_bus_id']]);

        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => $config['command_bus']['message_bus_id']]);

        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => $config['query_bus']['message_bus_id']]);
    }
}
