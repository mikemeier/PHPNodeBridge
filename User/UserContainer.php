<?php

namespace mikemeier\PHPNodeBridge\User;

use mikemeier\PHPNodeBridge\Store\StoreInterface;

class UserContainer
{

    /**
     * @var User[]
     */
    protected $users = array();

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

        $storeData = $this->store->get(self::STORE_KEY);
        $this->users = $storeData ?: array();
    }

    public function __destruct()
    {
        $this->store->set(self::STORE_KEY, $this->users);
    }

    /**
     * @return UserContainer
     */
    public function clear()
    {
        $this->users = array();
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
     * @param array User[]
     * @return UserContainer
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
        return $this;
    }
    
}