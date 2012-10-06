<?php

namespace mikemeier\PHPNodeBridge;

use Symfony\Component\EventDispatcher\EventDispatcher;

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
     * @var EventDispatcher 
     */
    protected $eventDispatcher;

    /**
     * @param Config $config
     * @param UserContainer $userContainer
     * @param Transport $transport
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        Config $config,
        UserContainer $userContainer,
        Transport $transport,
        EventDispatcher $eventDispatcher
    ){
        $this->config = $config;
        $this->userContainer = $userContainer;
        $this->transport = $transport;
        $this->eventDispatcher = $eventDispatcher;
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
     * @return string
     */
    public function getSocketBridgeUri()
    {
        return $this->config->getSocketBridgeUri();
    }
    
    /**
     * @param array $data
     * @return Response
     */
    public function process(array $data = array())
    {
        if(!$data){
            return new Response("No data received");
        }
        
        $response = new Response();
        
        $eventName = isset($data['event']) ? $data['event'] : null;
        $socketId = isset($data['socketId']) ? $data['socketId'] : null;
        $sessionId = isset($data['sessionId']) ? $data['sessionId'] : null;
        $paras = isset($data['data']) ? (array)$data['data'] : array();
        
        $event = new Event($response, $eventName, $socketId, $sessionId, $paras);
        
        $this->eventDispatcher->dispatch($eventName, $event);
        
        return $response;
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