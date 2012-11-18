<?php

namespace mikemeier\PHPNodeBridge\User;

use mikemeier\PHPNodeBridge\User\User;
use mikemeier\PHPNodeBridge\Store\StoreInterface;

class UserContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserContainer
     */
    protected $object;

    /**
     * @var User[]
     */
    public $users;

    /**
     * @var StoreInterface
     */
    public $store;

    const STORE_KEY = 'storeKey';

    protected function setUp()
    {
        $usersArray = array(
            'identificationA' => array(
                'socketIdA'
            ),
            'identificationB' => array(
                'socketIdB'
            )
        );

        $users = array();
        foreach($usersArray as $identification => $sockets){
            $user = new User($identification);
            foreach($sockets as $socketId){
                $user->addSocketId($socketId);
            }
            $users[$identification] = $user;
        }
        $this->users = $users;

        $store = $this->getMock('mikemeier\PHPNodeBridge\Store\StoreInterface');

        $self = $this;

        $store
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function()use($self){
                return $self->users;
            }))
        ;

        $store
            ->expects($this->any())
            ->method('set')
            ->will($this->returnCallback(function($key, $value)use($self){
                $self->users = $value;
                return $self->store;
            }))
        ;

        $this->store = $store;

        $this->object = new UserContainer($store, self::STORE_KEY);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::clear
     */
    public function testClear()
    {
        $this->object->clear();
        $this->assertSame(array(), $this->object->getAll());
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::add
     */
    public function testAddUser()
    {
        $user = new User('MyNewUser');
        $this->object->add($user);

        $this->assertSame($user, $this->object->getByIdentification('MyNewUser'));
        $this->assertContains($user, $this->object->getAll());
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::remove
     */
    public function testRemoveUser()
    {
        $user = reset($this->users);
        $this->object->remove($user);
        $this->assertNotContains($user, $this->object->getAll());
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::getAll
     */
    public function testGetAll()
    {
        $this->assertSame($this->users, $this->object->getAll());
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::getByIdentification
     */
    public function testGetByIdentification()
    {
        $user = reset($this->users);
        $this->assertSame($user, $this->object->getByIdentification($user->getIdentification()));
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::__construct
     */
    public function testStoreIsSet()
    {
        $this->assertAttributeEquals($this->store, 'store', $this->object);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::__construct
     */
    public function testStoreKeyIsSet()
    {
        $this->assertAttributeEquals(self::STORE_KEY, 'storeKey', $this->object);
    }
}
