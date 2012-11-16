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
     * @param TransportInterface $transport
     * @param IdentificationStrategyInterface $identificationStrategy
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        Config $config,
        UserContainer $userContainer,
        TransportInterface $transport,
        IdentificationStrategyInterface $identificationStrategy,
        EventDispatcher $eventDispatcher
    ){
        $this->config = $config;
        $this->userContainer = $userContainer;
        $this->transport = $transport;
        $this->identificationStrategy = $identificationStrategy;
        $this->eventDispatcher = $eventDispatcher;

        $this->registerEventListeners();
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
        
        return $this->getSocketIoServerUri().'/'. $this->config->getSocketIoApiTokenName() 
            .'?'.http_build_query($paras);
    }

    /**
     * @param Request $request
     * @return Response[]
     */
    public function process(Request $request)
    {
        $responses = array();

        $eventJSON = $request->request->get('events');

        if(!$eventJSON){
            return $responses;
        }

        $events = @json_decode($eventJSON, true);
        if(!$events){
            return $responses;
        }

        $socketId = $request->request->get('socketId');
        $identification = $request->request->get('identification');

        $user = $this->userContainer->getByIdentification($identification);
        if(!$user){
            $user = new User($identification);
        }
        $user->addSocketId($socketId);

        $eventNamePrefix = $this->config->getEventNamePrefix();

        foreach($events as $eventArray){
            $eventName = isset($eventArray['name']) ? $eventArray['name'] : null;

            if($eventName){
                $dispatchingEventName = $eventNamePrefix.'.'.$eventName;

                $eventParameters = isset($eventArray['parameters']) && is_array($eventArray['parameters']) ?
                    $eventArray['parameters'] : array();

                $response = new Response($eventName);
                $event = new Event($response, $user, $eventName, $eventParameters);

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

    protected function registerEventListeners()
    {
        $self = $this;

        $this->addEventListener('user.connection', function(Event $event)use($self){
            $user = $event->getUser();
            $event->addMessage(new Message('bridge', 'add socket '. $user->getLastAddedSocketId()));
            $self->getUserContainer()->add($user);
        });

        $this->addEventListener('user.disconnection', function(Event $event)use($self){
            $user = $event->getUser();
            $event->addMessage(new Message('bridge', 'remove socket '. $user->getLastAddedSocketId()));

            $user->removeSocketId($user->getLastAddedSocketId());
            if(!$user->hasSocketIds()){
                $self->getUserContainer()->remove($user);
            }
        });

        $this->addEventListener('server.restart', function(Event $event)use($self){
            $self->getUserContainer()->clear();
        });
    }
    
}