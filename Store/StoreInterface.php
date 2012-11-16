<?php

namespace mikemeier\PHPNodeBridge\Store;

interface StoreInterface
{

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     * @param $value
     * @return StoreInterface
     */
    public function set($key, $value);

    /**
     * @return StoreInterface
     */
    public function flush();

}