<?php

namespace mikemeier\PHPNodeBridge\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

	public function getConfigTreeBuilder()
	{
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mikemeier_php_node_bridge');

        $this->addConfigSection($rootNode);
        $this->addServiceSection($rootNode);

        return $treeBuilder;
	}

    protected function addServiceSection(ArrayNodeDefinition $parent)
    {
        $parent
            ->children()
				->arrayNode('service')->addDefaultsIfNotSet()->children()
                    ->scalarNode('encryption')->defaultValue('mikemeier_php_node_bridge.encryption')->end()
                    ->scalarNode('identificationstrategy')->defaultValue('mikemeier_php_node_bridge.identificationstrategy')->end()
                    ->scalarNode('transport')->defaultValue('mikemeier_php_node_bridge.transport')->end()
                    ->scalarNode('store')->defaultValue('mikemeier_php_node_bridge.store')->end()
				->end()
			->end()
        ;
    }

    protected function addConfigSection(ArrayNodeDefinition $parent)
    {
        $parent
            ->children()
				->arrayNode('config')->addDefaultsIfNotSet()->children()
                    ->scalarNode('socketIoClientUri')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('socketIoServerUri')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('socketIoApiTokenName')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('socketIoClientToken')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('socketIoServerToken')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('eventNamePrefix')->defaultValue('phpnodebridge')->end()
				->end()
			->end()
        ;
    }

}