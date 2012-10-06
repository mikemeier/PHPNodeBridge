<?php

namespace mikemeier\PHPNodeBridge;

class Message
{
    
    /**
     * @var array 
     */
    protected $data;
    
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }
    
    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
}