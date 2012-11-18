<?php

namespace mikemeier\PHPNodeBridge\Identification;

use mikemeier\PHPNodeBridge\Encryption\EncryptionInterface;

interface IdentificationStrategyInterface
{

    /**
     * @param null $decryptedIdentification
     * @return string
     */
    public function getEncryptedIdentification($decryptedIdentification = null);

    /**
     * @param $encryptedIdentifcation
     * @return string
     */
    public function decryptIdentification($encryptedIdentifcation);

    /**
     * @return IdentificationStrategyInterface
     */
    public function setEncryption(EncryptionInterface $encryption);

}