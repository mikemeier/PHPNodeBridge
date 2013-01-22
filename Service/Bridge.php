<?php

namespace mikemeier\PHPNodeBridge\Service;

use mikemeier\PHPNodeBridge\Identification\IdentificationStrategyInterface;
use mikemeier\PHPNodeBridge\Transport\TransportInterface;
use mikemeier\PHPNodeBridge\User\UserContainer;
use mikemeier\PHPNodeBridge\User\User;

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
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @var IdentificationStrategyInterface
     */
    protected $identificationStrategy;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param Config $config
     * @param UserContainer $userContainer
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        Config $config,
        UserContainer $userContainer,
        EventDispatcher $eventDispatcher
    ){
        $this->config = $config;
        $this->userContainer = $userContainer;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param IdentificationStrategyInterface $identificationStrategy
     * @return Bridge
     */
    public function setIdentificationStrategy(IdentificationStrategyInterface $identificationStrategy)
    {
        $this->identificationStrategy = $identificationStrategy;
        return $this;
    }

    /**
     * @param TransportInterface $transport
     * @return Bridge
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * @param $eventName
     * @param callable $listener
     * @param integer $priority
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $eventNameWithPrefix = $this->config->getEventNamePrefix().'.'.$eventName;
        $this->eventDispatcher->addListener($eventNameWithPrefix, function(Event $event)use($listener){
            call_user_func_array($listener, array_merge(array($event), $event->getParameters()));
        }, $priority);
    }
    
    /**
     * @return UserContainer 
     */
    public function getUserContainer()
    {
        return $this->userContainer;
    }
    
    /**
     * @return TransportInterface
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
        
        return $this->getSocketIoServerUri().'?'.http_build_query($paras);
    }

    /**
     * @param Request $request
     * @return Response[]
     */
    public function process(Request $request)
    {
        $responses = array();

        $userJSON = $request->request->get('users');
        $eventJSON = $request->request->get('events');

        if(!$userJSON || !$eventJSON){
            return $responses;
        }

        $users = @json_decode($userJSON, true);
        $events = @json_decode($eventJSON, true);
        if(!$users || !$events){
            return $responses;
        }

        $socketId = $request->request->get('socketId');
        $encryptedIdentification = $request->request->get('identification');

        if(!$encryptedIdentification || !$socketId){
            return $responses;
        }

        $identification = $this->identificationStrategy->decryptIdentification($encryptedIdentification);
        if(!$this->userContainer->getByIdentification($identification)){
            $events[] = array(
                'name' => 'user.newidentities',
                'parameters' => array(
                    'users' => array(new User($identification))
                )
            );
        }

        $userObjects = array();
        foreach($users as $encryptedIdentification => $socketIds){
            $userIdentification = $this->identificationStrategy->decryptIdentification($encryptedIdentification);

            $userObject = new User($userIdentification);
            $userObject->setSocketIds($socketIds);
            $userObjects[$userIdentification] = $userObject;

            if(in_array($socketId, $socketIds)){
                $user = $userObject;
            }
        }

        $removedUsers = array();
        foreach($this->userContainer->getAll() as $containerUser){
            if(!isset($userObjects[$containerUser->getIdentification()])){
                $removedUsers[] = $containerUser;
            }
        }

        if($removedUsers){
            $events[] = array(
                'name' => 'user.removedidentities',
                'parameters' => array(
                    'users' => $removedUsers
                )
            );
        }

        $this->userContainer->setUsers($userObjects);
        $eventNamePrefix = $this->getEventNamePrefix();

        foreach($events as $eventArray){
            $eventName = isset($eventArray['name']) ? $eventArray['name'] : null;

            if($eventName){
                $dispatchingEventName = $eventNamePrefix.'.'.$eventName;

                $eventParameters = isset($eventArray['parameters']) && is_array($eventArray['parameters']) ?
                    $eventArray['parameters'] : array();

                $response = new Response($eventName);
                $event = new Event($this, $response, $user, $identification, $socketId, $eventName, $eventParameters);

                $responses[] = $response;

                $this->eventDispatcher->dispatch($dispatchingEventName, $event);
            }
        }

        return $responses;
    }

    /**
     * @param Message $message
     * @param User $user
     * @return mixed
     */
    public function sendMessageToUser(Message $message, User $user)
    {
        return $this->transport->sendMessage($message, $user);
    }
    
    /**
     * @param Message $message
     * @param array $users
     * @return mixed
     */
    public function sendMessageToUsers(Message $message, array $users)
    {
        return $this->transport->sendMessageToUsers($message, $users);
    }

    /**
     * @param array $messages
     * @param User $user
     */
    public function sendMessagesToUser(array $messages, User $user)
    {
        return $this->transport->sendMessagesToUser($messages, $user);
    }

    /**
     * @param array $messages
     * @param array $users
     */
    public function sendMessagesToUsers(array $messages, array $users)
    {
        return $this->transport->sendMessagesToUsers($messages, $users);
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return string
     */
    public function getEventNamePrefix()
    {
        return $this->config->getEventNamePrefix();
    }
    
}