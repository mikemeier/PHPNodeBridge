<?php

namespace mikemeier\PHPNodeBridge\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

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

    const INTERNAL_EVENT_PREFIX = 'mikemeier_php_node_bridge';

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

        $this->registerEventListeners();
    }

    protected function registerEventListeners()
    {
        $self = $this;
        $eventDispatcher = $this->eventDispatcher;

        $eventDispatcher->addListener(self::INTERNAL_EVENT_PREFIX.'.user.connection', function(Event $event)use($self){
            $user = $event->getUser();
            $event->addMessage(new Message(array('message' => 'register user '. $user)));
            $self->getUserContainer()->add($user);
        });

        $eventDispatcher->addListener(self::INTERNAL_EVENT_PREFIX.'.user.disconnection', function(Event $event)use($self){
            $user = $event->getUser();
            $event->addMessage(new Message(array('message' => 'unregister user '. $user)));
            $self->getUserContainer()->remove($user);
        });
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
     * @param $clientIdentification
     * @return string
     */
    public function getSocketIoServerConnectionUri($clientIdentification)
    {
        $paras = array(
            'name' => $this->config->getSocketIoApiTokenName(),
            'token' => $this->config->getSocketIoClientToken(),
            'identification' => $clientIdentification
        );
        
        return $this->getSocketIoServerUri().'/'. $this->config->getSocketIoApiTokenName() 
            .'?'.http_build_query($paras);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response[]
     */
    public function process(Request $request)
    {
        $responses = array();

        print_r($request->request->all());

        $eventsJSON = $request->request->get('events');
        if(!$eventsJSON){
            return $responses;
        }

        $events = json_decode($eventsJSON, true);
        if(!$events){
            return $responses;
        }

        print_r($events);

        $socketId = $request->request->get('socketId');
        $identification = $request->request->get('identification');
        $user = new User($socketId, $identification);

        foreach($events as $eventArray){
            $eventName = isset($eventArray['name']) ? $eventArray['name'] : null;

            if($eventName){
                if(substr($eventName, 0, strlen(self::INTERNAL_EVENT_PREFIX)) === self::INTERNAL_EVENT_PREFIX){
                    $dispatchEventName = $eventName;
                }else{
                    $dispatchEventName = $this->config->getEventNamePrefix().'.'.$eventName;
                }

                $dispatchEventParameters = isset($eventArray['parameters']) && is_array($eventArray['parameters']) ?
                    $eventArray['parameters'] : array();

                $response = new Response($dispatchEventName);
                $event = new Event($response, $user, $dispatchEventName, $dispatchEventParameters);
                $responses[] = $response;

                $this->eventDispatcher->dispatch($dispatchEventName, $event);
            }
        }

        return $responses;
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