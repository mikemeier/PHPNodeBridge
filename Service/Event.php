<?php

namespace mikemeier\PHPNodeBridge\Service;

use mikemeier\PHPNodeBridge\User\User;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

class Event extends SymfonyEvent
{

    /**
     * @var Bridge
     */
    protected $bridge;

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
     * @param Bridge $bridge
     * @param Response $response
     * @param User $user
     * @param $identification
     * @param $socketId
     * @param $name
     * @param array $parameters
     */
    public function __construct(Bridge $bridge, Response $response, User $user, $identification, $socketId, $name, array $parameters = array())
    {
        $this->setName($name);

        $this->bridge = $bridge;
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
     * @return Bridge
     */
    public function getBridge()
    {
        return $this->bridge;
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