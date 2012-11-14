<?php

namespace mikemeier\PHPNodeBridge\Service;

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
    protected $socketIoClientToken;
    
    /**
     * @var string 
     */
    protected $socketIoServerToken;
    
    /**
     * @var string 
     */
    protected $socketIoApiTokenName;
    
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
    
    public function setSocketIoClientUri($uri)
    {
        $this->socketIoClientUri = $uri;
        
        return $this;
    }
    
    public function getSocketIoClientUri()
    {
        return $this->socketIoClientUri;
    }
    
    public function setSocketIoServerUri($uri)
    {
        $this->socketIoServerUri = $uri;
        
        return $this;
    }
    
    public function getSocketIoServerUri()
    {
        return $this->socketIoServerUri;
    }
    
    public function getSocketIoClientToken()
    {
        return $this->socketIoClientToken;
    }
    
    public function setSocketIoClientToken($token)
    {
        $this->socketIoClientToken = $token;
        
        return $this;
    }
    
    public function getSocketIoServerToken()
    {
        return $this->socketIoServerToken;
    }
    
    public function setSocketIoServerToken($token)
    {
        $this->socketIoServerToken = $token;
        
        return $this;
    }
    
    public function getSocketIoApiTokenName()
    {
        return $this->socketIoApiTokenName;
    }
    
    public function setSocketIoApiTokenName($name)
    {
        $this->socketIoApiTokenName = $name;
        
        return $this;
    }
    
}