<?php

namespace mikemeier\PHPNodeBridge\Identification;

use mikemeier\PHPNodeBridge\Encryption\EncryptionInterface;

use Symfony\Component\HttpFoundation\Session\Session;

class IdentificationStrategy implements IdentificationStrategyInterface
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var EncryptionInterface
     */
    protected $encryption;

    /**
     * string
     */
    protected $salt;

    public function __construct(Session $session, EncryptionInterface $encryption, $salt)
    {
        $this->session = $session;
        $this->encryption = $encryption;
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getEncryptedIdentification($decryptedIdentification = null)
    {
        if(null === $decryptedIdentification){
            $this->session->start();
            $decryptedIdentification = $this->session->getId();
        }

        return base64_encode($this->encryption->encrypt($decryptedIdentification, $this->salt));
    }

    /**
     * @param $encryptedIdentifcation
     * @return string
     */
    public function decryptIdentification($encryptedIdentifcation)
    {
        return $this->encryption->decrypt(base64_decode($encryptedIdentifcation), $this->salt);
    }

}