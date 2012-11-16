<?php

namespace mikemeier\PHPNodeBridge\Service;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

class Event extends SymfonyEvent
{

    /**
     * @var Response 
     */
    protected $response;
    
    /**
     * @var User 
     */
    protected $user;
    
    /**
     * @var string 
     */
    protected $name;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param Response $response
     * @param User $user
     * @param $name
     * @param array $parameters
     */
    public function __construct(Response $response, User $user, $name, array $parameters = array())
    {
        $this->setName($name);

        $this->response = $response;
        $this->user = $user;
        $this->parameters = $parameters;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param Message $message
     * @return Event
     */
    public function addMessage(Message $message)
    {
        $this->response->addMessage($message);

        return $this;
    }
    
}