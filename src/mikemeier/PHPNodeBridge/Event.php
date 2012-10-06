<?php

namespace mikemeier\PHPNodeBridge;

class Event extends \Symfony\Component\EventDispatcher\Event
{
    
    /**
     * @var Response 
     */
    protected $response;
    
    /**
     * @var string 
     */
    protected $name;
    
    /**
     * @var string 
     */
    protected $socketId;
    
    /**
     * @var string 
     */
    protected $sessionId;
    
    /**
     * @var array 
     */
    protected $paras;
    
    /**
     * @param Response $response
     * @param string $name
     * @param string $socketId
     * @param string $sessionId
     * @param array $paras
     */
    public function __construct(Response $response, $name, $socketId, $sessionId, array $paras = array())
    {
        $this->response = $response;
        $this->name = $name;
        $this->socketId = $socketId;
        $this->sessionId = $sessionId;
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
     * @return string
     */
    public function getSocketId()
    {
        return $this->socketId;
    }
    
    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }
    
    /**
     * @return array
     */
    public function getParas()
    {
        return $this->paras;
    }
    
}