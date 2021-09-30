<?php

namespace SoureCode\Bundle\Cqrs\DependencyInjection;

use SoureCode\Component\Cqrs\CommandHandlerInterface;
use SoureCode\Component\Cqrs\EventHandlerInterface;
use SoureCode\Component\Cqrs\QueryHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\InstanceofConfigurator;
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
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        $loader->load('services.php');

        $eventBusDefinition = $container->getDefinition('soure_code.cqrs.event_bus');
        $eventBusDefinition->addArgument(new Reference($config['event_bus']['message_bus_id']));

        $commandBusDefinition = $container->getDefinition('soure_code.cqrs.command_bus');
        $commandBusDefinition->addArgument(new Reference($config['command_bus']['message_bus_id']));

        $queryBusDefinition = $container->getDefinition('soure_code.cqrs.query_bus');
        $queryBusDefinition->addArgument(new Reference($config['query_bus']['message_bus_id']));

        $eventBusHandlerDefinition = new Definition();
        $eventBusHandlerDefinition->setInstanceofConditionals([
        ]);


        $container->registerForAutoconfiguration(EventHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => $config['event_bus']['message_bus_id']]);

        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => $config['command_bus']['message_bus_id']]);

        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => $config['query_bus']['message_bus_id']]);
    }
}
