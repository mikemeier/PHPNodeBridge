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
    
    const STORE_KEY = 'phpnodebridge.users';

    /**
     * @param StoreInterface $store
     * @param string $storeKey
     */
    public function __construct(StoreInterface $store, $storeKey = self::STORE_KEY)
    {
        $this->store = $store;
        $this->storeKey = $storeKey;
    }

    /**
     * @return UserContainer
     */
    public function clear()
    {
        $this->setUsersToStore(array());
    }
    
    /**
     * @param User $user
     * @retur UserContainer
     */
    public function add(User $user)
    {
        $users = $this->getUsersFromStore();
        $users[$user->getIdentification()] = $user;
        $this->setUsersToStore($users);
        return $this;
    }
    
    /**
     * @return User[]
     */
    public function getAll()
    {
        return $this->getUsersFromStore();
    }
    
    /**
     * @param string $identification
     * @return User|null
     */
    public function getByIdentification($identification)
    {
        $users = $this->getUsersFromStore();
        return isset($users[$identification]) ?
            $users[$identification] : null;
    }
    
    /**
     * @param User $user
     * @return UserContainer 
     */
    public function remove(User $user)
    {
        $users = $this->getUsersFromStore();
        unset($users[$user->getIdentification()]);
        $this->setUsersToStore($users);
        return $this;
    }

    protected function getUsersFromStore()
    {
        return $this->store->get($this->storeKey);
    }

    protected function setUsersToStore($users)
    {
        $this->store->set($this->storeKey, $users);
    }
    
}