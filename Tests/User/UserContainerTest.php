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
    protected $users;

    /**
     * @var StoreInterface
     */
    protected $store;

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

        $store
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->users))
        ;

        $this->store = $store;

        $this->object = new UserContainer($store, self::STORE_KEY);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::__destruct
     */
    public function test__destruct()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::add
     */
    public function testAdd()
    {
        $user = new User('testSocketId', 'testIdentification');

        $this->object->add($user);
        $this->assertAttributeContains($user, 'users', $this->object);
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

    /**
     * @covers mikemeier\PHPNodeBridge\User\UserContainer::remove
     */
    public function testRemove()
    {
        $user = reset($this->users);

        $this->object->remove($user);
        $this->assertAttributeNotContains($user, 'users', $this->object);
    }
}
