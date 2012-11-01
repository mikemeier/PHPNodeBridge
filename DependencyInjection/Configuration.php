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

        return $treeBuilder;
	}
}