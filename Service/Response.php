<?php

namespace mikemeier\PHPNodeBridge\Service;

class Response
{
    
    /**
     * @var array 
     */
    protected $data;
    
    /**
     * @param array $content
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }
    
    /**
     * @param string $namespace
     * @param string $key
     * @param mixed $value
     */
    public function addData($namespace, $key, $value)
    {
        if(!isset($this->data[$namespace])){
            $this->data[$namespace] = array();
        }
        
        $this->data[$namespace][$key] = $value;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->data);
    }
    
}