<?php

    require_once '../vendor/autoload.php';
    
    $config = new \mikemeier\PHPNodeBridge\Config(array(
        'socketIoClientUri' => 'http://node.local:8080/socket.io/socket.io.js',
        'socketIoServerUri' => 'http://node.local:8080',
        'socketBridgeUri' => 'http://node.local/bridge.php'
    ));
    
    $transport = new \mikemeier\PHPNodeBridge\Transport($config);
    $store = new \mikemeier\PHPNodeBridge\Store(__DIR__.'/cache/userstore.txt');
    
    $userContainer = new mikemeier\PHPNodeBridge\UserContainer($store);
    
    $eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
    $eventDispatcher->addListener('bridge.connection', function(\mikemeier\PHPNodeBridge\Event $event)use($userContainer){
        $user = new mikemeier\PHPNodeBridge\User(
            $event->getSocketId(),
            $event->getSessionId()
        );
        $userContainer->add($user);
    });
    
    $eventDispatcher->addListener('bridge.disconnection', function(\mikemeier\PHPNodeBridge\Event $event)use($userContainer){
        $userContainer->removeBySocketId($event->getSocketId());
    });
    
    return new \mikemeier\PHPNodeBridge\Bridge($config, $userContainer, $transport, $eventDispatcher);