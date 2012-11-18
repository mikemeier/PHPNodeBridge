<?php

namespace mikemeier\PHPNodeBridge\Store\Adapter;

use mikemeier\PHPNodeBridge\Store\StoreInterface;

use Symfony\Component\Filesystem\Filesystem;

class FileAdapter implements StoreInterface
{
    
    /**
     * @var string 
     */
    protected $file;
    
    /**
     * @var array 
     */
    protected $data = array();

    /**
     * @var bool
     */
    protected $autoFlush = true;

    /**
     * @param $file
     * @param bool $autoFlush
     */
    public function __construct($file, $autoFlush = true)
    {
        $this->file = $file;
        $this->autoFlush = $autoFlush;

        if(file_exists($file) && is_readable($file)){
            $this->data = unserialize(file_get_contents($file));
        }
    }

    public function __destruct()
    {
        if(true === $this->autoFlush){
            $this->flush();
        }
    }

    /**
     * @param string $key
     * @return mixed 
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     * @return FileAdapter|StoreInterface
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @return FileAdapter|StoreInterface
     * @throws \InvalidArgumentException
     */
    public function flush()
    {
        $dir = dirname($this->file);

        if(!is_dir($dir) || !is_writable($dir)){
            $filesystem = new Filesystem();
            $filesystem->mkdir($dir);
        }

        if(!is_dir($dir) || !is_writable($dir)){
            throw new \InvalidArgumentException("Dir '$dir' not valid");
        }

        file_put_contents($this->file, serialize($this->data));

        return $this;
    }
    
}