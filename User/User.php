<?php

namespace mikemeier\PHPNodeBridge\User;

class User
{
    
    /**
     * @var string 
     */
    protected $socketId;
    
    /**
     * @var string 
     */
    protected $identification;
    
    /**
     * @param string $socketId
     * @param string $identification 
     */
    public function __construct($socketId, $identification)
    {
        $this->socketId = $socketId;
        $this->identification = $identification;
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
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'User with SocketId: '. $this->getSocketId();
    }
    
}