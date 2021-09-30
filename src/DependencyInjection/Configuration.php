<?php

namespace SoureCode\Bundle\Cqrs\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('soure_code_cqrs');

        /**
         * @var ArrayNodeDefinition $rootNode
         */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append($this->getEventBusConfig());
        $rootNode->append($this->getCommandBusConfig());
        $rootNode->append($this->getQueryBusConfig());

        return $treeBuilder;
    }

    private function getEventBusConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('event_bus');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('message_bus_id')
            ->defaultValue('event.bus');
        // @formatter:on

        return $rootNode;
    }

    private function getCommandBusConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('command_bus');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('message_bus_id')
            ->defaultValue('command.bus');
        // @formatter:on

        return $rootNode;
    }

    private function getQueryBusConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('query_bus');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('message_bus_id')
            ->defaultValue('query.bus');
        // @formatter:on

        return $rootNode;
    }
}
