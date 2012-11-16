<?php

namespace mikemeier\PHPNodeBridge\Store\Adapter;

use org\bovigo\vfs\vfsStream;

class FileAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FileAdapter
     */
    protected $object;

    protected $path = null;
    protected $dir = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->path = __DIR__.'/TestDirectory/store.txt';
        $this->dir = dirname($this->path);

        $this->root = vfsStream::setup($this->dir);
        $this->object = new FileAdapter($this->path);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::set
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::get
     */
    public function testGet()
    {
        $this->object->set('key', 'value');
        $this->assertEquals('value', $this->object->get('key'));
    }

}
