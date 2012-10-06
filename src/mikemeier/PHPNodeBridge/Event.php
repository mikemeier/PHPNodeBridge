<?php

namespace mikemeier\PHPNodeBridge;

class Event extends \Symfony\Component\EventDispatcher\Event
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
    protected $paras;
    
    /**
     * @param Response $response
     * @param User $user
     * @param string $name
     * @param array $paras
     */
    public function __construct(Response $response, User $user, $name, array $paras = array())
    {
        $this->response = $response;
        $this->name = $name;
        $this->user = $user;
        $this->paras = $paras;
    }
    
    /**
     * @param string $key
     * @param mixed $value
     * @return Event
     */
    public function addResponseData($key, $value)
    {
        $this->response->addData($this->name, $key, $value);
        
        return $this;
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
    public function getParas()
    {
        return $this->paras;
    }
    
}