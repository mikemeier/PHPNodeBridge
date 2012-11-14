<?php

namespace mikemeier\PHPNodeBridge\Service;

class UserContainer
{
    
    /**
     * @var Store 
     */
    protected $store;
    
    /**
     * @var string 
     */
    protected $storeKey;
    
    /**
     * @var User[] 
     */
    protected $users = array();
    
    const STORE_KEY = 'phpnodebridge.users';
    
    /**
     * @param array $users 
     */
    public function __construct(Store $store, $storeKey = self::STORE_KEY)
    {
        $this->store = $store;
        $this->users = $store->get($storeKey);
    }
    
    public function __destruct()
    {
        $this->store->set($this->storeKey, $this->users);
    }
    
    /**
     * @param User $user 
     */
    public function add(User $user)
    {
        $this->users[$user->getSocketId()] = $user;
    }
    
    /**
     * @return Users[] 
     */
    public function getAll()
    {
        return $this->users;
    }
    
    /**
     * @param string $identification
     * @return User[]
     */
    public function getByIdentification($identification)
    {
        $users = array();
        
        foreach($this->users as $user){
            if($identification == $user->getIdentification()){
                $users[] = $user;
            }
        }
        
        return $users;
    }
    
    /**
     * @param string $socketId
     * @return User|null 
     */
    public function getBySocketId($socketId)
    {
        return isset($this->users[$socketId]) ? 
            $this->users[$socketId] : null;
    }
    
    /**
     * @param User $user
     * @return UserContainer 
     */
    public function remove(User $user)
    {
        unset($this->users[$user->getSocketId()]);
        
        return $this;
    }
    
}