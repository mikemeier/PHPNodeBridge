<?php

namespace mikemeier\PHPNodeBridge\Encryption;

interface EncryptionInterface
{

    /**
     * @param string $data
     * @param string $key
     */
    public function encrypt($data, $key);

    /**
     * @param string $data
     * @param string $key
     */
    public function decrypt($data, $key);

}