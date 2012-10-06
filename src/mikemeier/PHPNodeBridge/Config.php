<?php

namespace mikemeier\PHPNodeBridge;

class Config
{
    
    /**
     * @var string 
     */
    protected $socketIoClientUri;
    
    /**
     * @var string 
     */
    protected $socketIoServerUri;
    
    /**
     * @var string 
     */
    protected $socketBridgeUri;
    
    /**
     * @param array $options
     * @throws \InvalidArgumentException 
     */
    public function __construct(array $options = array())
    {
        foreach($options as $key => $value){
            $methodName = 'set'. ucfirst($key);
            if(method_exists($this, $methodName)){
                $this->$methodName($value);
            }else{
                throw new \InvalidArgumentException("Option $key not allowed");
            }
        }
    }
    
    public function setSocketBridgeUri($uri)
    {
        $this->socketBridgeUri = $uri;
    }
    
    public function getSocketBridgeUri()
    {
        return $this->socketBridgeUri;
    }
    
    public function setSocketIoClientUri($uri)
    {
        $this->socketIoClientUri = $uri;
    }
    
    public function getSocketIoClientUri()
    {
        return $this->socketIoClientUri;
    }
    
    public function setSocketIoServerUri($uri)
    {
        $this->socketIoServerUri = $uri;
    }
    
    public function getSocketIoServerUri()
    {
        return $this->socketIoServerUri;
    }
    
}