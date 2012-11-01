<?php

namespace mikemeier\PHPNodeBridge\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class mikemeierPHPNodebridgeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.xml');

        $this->registerContainerParametersRecursive($container, $this->getAlias(), $config);
    }

    public function getAlias()
    {
        return 'mikemeier_php_node_bridge';
    }

    protected function registerContainerParametersRecursive(ContainerBuilder $container, $alias, $config)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($config), \RecursiveIteratorIterator::SELF_FIRST);

        foreach($iterator as $value){
            $path = array();

            for($i = 0; $i <= $iterator->getDepth(); $i++){
                $path[] = $iterator->getSubIterator($i)->key();
            }

            $key = $alias . '.' . implode(".", $path);
            $container->setParameter($key, $value);
        }
    }
}
