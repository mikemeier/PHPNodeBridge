<?php

namespace mikemeier\PHPNodeBridge;

class User
{
    
    /**
     * @var string 
     */
    protected $socketId;
    
    /**
     * @var string 
     */
    protected $sessionId;
    
    /**
     * @param string $socketId
     * @param string $sessionId 
     */
    public function __construct($socketId, $sessionId)
    {
        $this->socketId = $socketId;
        $this->sessionId = $sessionId;
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
    
}