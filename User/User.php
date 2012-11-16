<?php

namespace mikemeier\PHPNodeBridge\User;

class User
{

    /**
     * @var string 
     */
    protected $identification;

    /**
     * @var string
     */
    protected $socketIds = array();
    
    /**
     * @param string $identification 
     */
    public function __construct($identification)
    {
        $this->identification = $identification;
    }

    /**
     * @return array
     */
    public function getSocketIds()
    {
        return $this->socketIds;
    }

    /**
     * @return string
     */
    public function getLastAddedSocketId()
    {
        return array_pop($this->socketIds);
    }

    /**
     * @param string $socketId
     * @return User
     */
    public function addSocketId($socketId)
    {
        if(!$this->hasSocketId($socketId)){
            $this->socketIds[] = $socketId;
        }

        return $this;
    }

    /**
     * @return User
     */
    public function removeSocketId($socketId)
    {
        $this->socketIds = array_diff($this->socketIds, array($socketId));

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSocketIds()
    {
        return count($this->socketIds) > 0;
    }

    /**
     * @param string $socketId
     * @return bool
     */
    public function hasSocketId($socketId)
    {
        return in_array($socketId, $this->socketIds);
    }
    
    /**
     * @return string 
     */
    public function getIdentification()
    {
        return $this->identification;
    }
    
}