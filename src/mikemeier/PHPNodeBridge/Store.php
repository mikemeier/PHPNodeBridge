<?php

namespace mikemeier\PHPNodeBridge;

class Store
{
    
    /**
     * @var string 
     */
    protected $file;
    
    /**
     * @var array 
     */
    protected $data = array();
    
    /**
     * @param string $file 
     */
    public function __construct($file)
    {
        $this->file = $file;
        
        if(file_exists($file) && is_readable($file)){
            $this->data = unserialize(file_get_contents($file));
        }
    }
    
    public function __destruct()
    {
        file_put_contents($this->file, serialize($this->data));
    }
    
    /**
     * @param string $key
     * @return mixed 
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
    
    /**
     * @param string $key
     * @param mixed $value 
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
    
}