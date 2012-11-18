<?php

namespace mikemeier\PHPNodeBridge;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $encryptionServiceId = $container->getParameter('mikemeier_php_node_bridge.service.encryption');
        $identificationStrategyServiceId = $container->getParameter('mikemeier_php_node_bridge.service.identificationstrategy');
        $transportServiceId = $container->getParameter('mikemeier_php_node_bridge.service.transport');
        $storeServiceId = $container->getParameter('mikemeier_php_node_bridge.service.store');

        $identificationService = $container->getDefinition($identificationStrategyServiceId);
        $twigExtensionService = $container->getDefinition('mikemeier_php_node_bridge.twigextension');
        $bridgeService = $container->getDefinition('mikemeier_php_node_bridge.bridge');
        $userContainerService = $container->getDefinition('mikemeier_php_node_bridge.usercontainer');

        $identificationService->addMethodCall('setEncryption', array(new Reference($encryptionServiceId)));

        $twigExtensionService->addArgument(new Reference($identificationStrategyServiceId));

        $bridgeService->addMethodCall('setIdentificationStrategy', array(new Reference($identificationStrategyServiceId)));
        $bridgeService->addMethodCall('setTransport', array(new Reference($transportServiceId)));
        $bridgeService->addMethodCall('registerEventListeners');

        $userContainerService->addArgument(new Reference($storeServiceId));
    }
}