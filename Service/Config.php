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
     * @var string
     */
    protected $eventNamePrefix;
    
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

    /**
     * @param string $uri
     * @return Config
     */
    public function setSocketIoClientUri($uri)
    {
        $this->socketIoClientUri = $uri;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getSocketIoClientUri()
    {
        return $this->socketIoClientUri;
    }

    /**
     * @param string $uri
     * @return Config
     */
    public function setSocketIoServerUri($uri)
    {
        $this->socketIoServerUri = $uri;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getSocketIoServerUri()
    {
        return $this->socketIoServerUri;
    }

    /**
     * @return string
     */
    public function getSocketIoClientToken()
    {
        return $this->socketIoClientToken;
    }

    /**
     * @param string $token
     * @return Config
     */
    public function setSocketIoClientToken($token)
    {
        $this->socketIoClientToken = $token;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getSocketIoServerToken()
    {
        return $this->socketIoServerToken;
    }

    /**
     * @param string $token
     * @return Config
     */
    public function setSocketIoServerToken($token)
    {
        $this->socketIoServerToken = $token;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getSocketIoApiTokenName()
    {
        return $this->socketIoApiTokenName;
    }

    /**
     * @param string $name
     * @return Config
     */
    public function setSocketIoApiTokenName($name)
    {
        $this->socketIoApiTokenName = $name;
        
        return $this;
    }

    /**
     * @param sting $prefix
     * @return Config
     */
    public function setEventNamePrefix($prefix)
    {
        $this->eventNamePrefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventNamePrefix()
    {
        return $this->eventNamePrefix;
    }
    
}