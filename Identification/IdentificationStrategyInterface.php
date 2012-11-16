<?php

namespace mikemeier\PHPNodeBridge\Identification;

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

}