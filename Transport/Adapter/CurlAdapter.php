<?php

namespace mikemeier\PHPNodeBridge\Transport\Adapter;

use mikemeier\PHPNodeBridge\Service\Message;
use mikemeier\PHPNodeBridge\Service\Config;
use mikemeier\PHPNodeBridge\Transport\TransportInterface;
use mikemeier\PHPNodeBridge\User\User;

class CurlAdapter implements TransportInterface
{
    
    /**
     * @var Config 
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Message $message
     * @param User $user
     */
    public function sendMessageToUser(Message $message, User $user)
    {
        $ch = $this->getCurlHandle();
    }

    /**
     * @param array $messages
     * @param User $user
     */
    public function sendMessagesToUser(array $messages, User $user)
    {
        
    }

    /**
     * @param Message $message
     * @param array $users
     */
    public function sendMessageToUsers(Message $message, array $users)
    {
        
    }

    /**
     * @param array $messages
     * @param array $users
     */
    public function sendMessagesToUsers(array $messages, array $users)
    {
        
    }

    /**
     * @return resource
     */
    protected function getCurlHandle()
    {
        $ch = curl_init();
        
        return $ch;
    }
    
}