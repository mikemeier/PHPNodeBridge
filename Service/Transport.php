<?php

namespace mikemeier\PHPNodeBridge\Service;

class Transport
{
    
    /**
     * @var Config 
     */
    protected $config;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    
    public function sendMessageToUser(Message $message, User $user)
    {
        $ch = $this->getCurlHandle();
    }
    
    public function sendMessagesToUser(array $messages, User $user)
    {
        
    }
    
    public function sendMessageToUsers(Message $message, array $users)
    {
        
    }
    
    public function sendMessagesToUsers(array $messages, array $users)
    {
        
    }
    
    protected function getCurlHandle()
    {
        $ch = curl_init();
        
        return $ch;
    }
    
}