<?php

    require_once '../vendor/autoload.php';
    
    $config = new \mikemeier\PHPNodeBridge\Config(array(
        'socketIoClientUri' => 'http://node.local:8080/socket.io/socket.io.js',
        'socketIoServerUri' => 'http://node.local:8080',
        
        'socketIoApiTokenName' => 'demo',
        'socketIoClientToken' => 'client',
        'socketIoServerToken' => 'server'
    ));
    
    $transport = new \mikemeier\PHPNodeBridge\Transport($config);
    $store = new \mikemeier\PHPNodeBridge\Store(__DIR__.'/cache/userstore.txt');
    
    $userContainer = new \mikemeier\PHPNodeBridge\UserContainer($store);
    
    $eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
    
    $bridge = new \mikemeier\PHPNodeBridge\Bridge($config, $userContainer, $transport, $eventDispatcher);
    
    $eventDispatcher->addListener('bridge.connection', function(\mikemeier\PHPNodeBridge\Event $event)use($userContainer){
        $user = $event->getUser();
        $event->addResponseData('result', 'add user: '. $user);
        $userContainer->add($user);
    });
    
    $eventDispatcher->addListener('bridge.disconnection', function(\mikemeier\PHPNodeBridge\Event $event)use($userContainer){
        $user = $event->getUser();
        $event->addResponseData('result', 'remove user: '. $user);
        $userContainer->remove($user);
    });
    
    return $bridge;