<?php

namespace mikemeier\PHPNodeBridge;

use mikemeier\PHPNodeBridge\Transport;

class Bridge
{
    
    /**
     * @var Config 
     */
    protected $config;
    
    /**
     * @var UserContainer 
     */
    protected $userContainer;
    
    /**
     * @var Transport 
     */
    protected $transport;
    
    /**
     * @param UserContainer $userContainer
     * @param Transport
     */
    public function __construct(
        Config $config,
        UserContainer $userContainer,
        Transport $transport
    ){
        $this->config = $config;
        $this->userContainer = $userContainer;
        $this->transport = $transport;
    }
    
    /**
     * @return UserContainer 
     */
    public function getUserContainer()
    {
        return $this->userContainer;
    }
    
    /**
     * @return Transport 
     */
    public function getTransport()
    {
        return $this->transport;
    }
    
    /**
     * @return string 
     */
    public function getSocketIoClientUri()
    {
        return $this->config->getSocketIoClientUri();
    }
    
    /**
     * @return string 
     */
    public function getSocketIoServerUri()
    {
        return $this->config->getSocketIoServerUri();
    }
    
    /**
     * @param Message $message
     * @param User $user
     * @return MessageResult
     */
    public function sendMessageToUser(Message $message, User $user)
    {
        return $this->transport->sendMessage($message, $user);
    }
    
    /**
     * @param Message $message
     * @param array $users
     * @return MessageResult[]
     */
    public function sendMessageToUsers(Message $message, array $users)
    {
        return $this->transport->sendMessageToUsers($message, $users);
    }
    
    /**
     * @param array $messages
     * @param User $user
     * @return MessageResult[]
     */
    public function sendMessagesToUser(array $messages, User $user)
    {
        return $this->transport->sendMessagesToUser($messages, $user);
    }
    
    /**
     * @param array $messages
     * @param array $users
     * @return MessageResult[] 
     */
    public function sendMessagesToUsers(array $messages, array $users)
    {
        return $this->transport->sendMessagesToUsers($messages, $users);
    }
    
}