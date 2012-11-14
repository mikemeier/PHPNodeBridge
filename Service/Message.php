<?php

namespace mikemeier\PHPNodeBridge\Service;

class Message
{
    
    /**
     * @var array 
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
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