<?php

namespace mikemeier\PHPNodeBridge\Encryption\Adapter;

class McryptAdapterTest extends \PHPUnit_Framework_TestCase
{

    const DATA = 'testData';
    const SALT = 'testSalt';

    /**
     * @var McryptAdapter
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new McryptAdapter;
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Encryption\Adapter\McryptAdapter::encrypt
     */
    public function testEncrypt()
    {
        $encrypted = $this->object->encrypt(self::DATA, self::SALT);
        $decrypted = $this->object->decrypt($encrypted, self::SALT);

        $this->assertEquals(self::DATA, $decrypted);
    }
}
