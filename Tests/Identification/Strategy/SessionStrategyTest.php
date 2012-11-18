<?php

namespace mikemeier\PHPNodeBridge\Identification\Strategy;

use mikemeier\PHPNodeBridge\Encryption\EncryptionInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStrategyTest extends \PHPUnit_Framework_TestCase
{

    const SALT = 'testSalt';
    const SESSION_ID = 'testSessionId';

    const IDENTIFICATION_ENCRYPTED = 'identificationEncrypted';
    const IDENTIFICATION_DECRYPTED = 'identificationDecrypted';

    /**
     * @var SessionStrategy
     */
    protected $object;

    protected function setUp()
    {
        $session = $this->getSession();
        $encryption = $this->getEncryption();

        $this->object = new SessionStrategy($session, self::SALT);
        $this->object->setEncryption($encryption);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Identification\Strategy\SessionStrategy::decryptIdentification
     */
    public function testDecryptIdentification()
    {
        $encrypted = $this->object->getEncryptedIdentification();
        $decrypted = $this->object->decryptIdentification($encrypted);

        $this->assertEquals(self::IDENTIFICATION_DECRYPTED, $decrypted);
    }

    /**
     * @return SessionInterface
     */
    protected function getSession()
    {
        $mock = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');

        $mock
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(self::SESSION_ID))
        ;

        $mock
            ->expects($this->any())
            ->method('start')
            ->will($this->returnValue(true))
        ;

        return $mock;
    }

    /**
     * @return EncryptionInterface
     */
    protected function getEncryption()
    {
        $mock = $this->getMock('mikemeier\PHPNodeBridge\Encryption\EncryptionInterface');

        $mock
            ->expects($this->any())
            ->method('encrypt')
            ->will($this->returnValue(self::IDENTIFICATION_ENCRYPTED))
        ;

        $mock
            ->expects($this->any())
            ->method('decrypt')
            ->will($this->returnValue(self::IDENTIFICATION_DECRYPTED))
        ;

        return $mock;
    }

}
