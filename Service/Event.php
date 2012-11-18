<?php

namespace mikemeier\PHPNodeBridge\Service;

use mikemeier\PHPNodeBridge\User\User;

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
    protected $identification;

    /**
     * @var string
     */
    protected $socketId;

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
     * @param string $identification
     * @param string $socketId
     * @param $name
     * @param array $parameters
     */
    public function __construct(Response $response, User $user, $identification, $socketId, $name, array $parameters = array())
    {
        $this->setName($name);

        $this->response = $response;
        $this->user = $user;
        $this->identification = $identification;
        $this->socketId = $socketId;
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
     * @return string
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @return string
     */
    public function getSocketId()
    {
        return $this->socketId;
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