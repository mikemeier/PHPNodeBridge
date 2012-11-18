<?php

namespace mikemeier\PHPNodeBridge\Store\Adapter;

use mikemeier\PHPNodeBridge\Store\StoreInterface;

class APCAdapter implements StoreInterface
{

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * @param $key
     * @param $value
     * @return APCAdpater|StoreInterface
     */
    public function set($key, $value)
    {
        apc_store($key, $value);

        return $this;
    }

}