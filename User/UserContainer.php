<?php

namespace mikemeier\PHPNodeBridge\User;

use mikemeier\PHPNodeBridge\Store\StoreInterface;

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
     * @param StoreInterface $store
     * @param string $storeKey
     */
    public function __construct(StoreInterface $store, $storeKey = self::STORE_KEY)
    {
        $this->store = $store;
        $this->storeKey = $storeKey;
        $this->users = $store->get($storeKey);
    }
    
    public function __destruct()
    {
        $this->store->set($this->storeKey, $this->users);
    }

    /**
     * @return UserContainer
     */
    public function clear()
    {
        $this->users = array();
    }
    
    /**
     * @param User $user 
     */
    public function add(User $user)
    {
        $this->users[$user->getIdentification()] = $user;
    }
    
    /**
     * @return User[]
     */
    public function getAll()
    {
        return $this->users;
    }
    
    /**
     * @param string $identification
     * @return User|null
     */
    public function getByIdentification($identification)
    {
        return isset($this->users[$identification]) ?
            $this->users[$identification] : null;
    }
    
    /**
     * @param User $user
     * @return UserContainer 
     */
    public function remove(User $user)
    {
        unset($this->users[$user->getIdentification()]);
        
        return $this;
    }
    
}