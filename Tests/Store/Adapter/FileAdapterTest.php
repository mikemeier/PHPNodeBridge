<?php

namespace mikemeier\PHPNodeBridge\Store\Adapter;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

class FileAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @string
     */
    protected $path;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        vfsStream::setup('root');
        $this->path = vfsStream::url('root/userstore.txt');
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::__constructor
     */
    public function testConstructorSetFilePath()
    {
        $fileAdapter = new FileAdapter($this->path, false);
        $this->assertAttributeEquals($this->path, 'file', $fileAdapter);
    }

    /**
     * @covers \mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::set
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::get
     */
    public function testGetAndSet()
    {
        $fileAdapter = new FileAdapter($this->path, false);
        $fileAdapter->set('key', 'value');
        $this->assertEquals('value', $fileAdapter->get('key'));
    }

    /**
     * @covers \mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::set
     */
    public function testSet()
    {
        $fileAdapter = new FileAdapter($this->path, false);
        $fileAdapter->set('key', 'value');
        $this->assertAttributeEquals(array('key' => 'value'), 'data', $fileAdapter);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::flush
     */
    public function testFileSystemWrite()
    {
        $fileAdapter = new FileAdapter($this->path, false);
        $this->assertFileNotExists($this->path);
        $fileAdapter->flush();
        $this->assertFileExists($this->path);
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::__construct
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::flush
     * @covers \mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::set
     * @covers mikemeier\PHPNodeBridge\Store\Adapter\FileAdapter::get
     */
    public function testFileSystemRead()
    {
        $fileAdapter = new FileAdapter($this->path, false);
        $fileAdapter->set('mykey', array('foo' => 'bar'));
        $fileAdapter->flush();

        $fileAdapter = new FileAdapter($this->path, false);
        $this->assertSame($fileAdapter->get('mykey'), array('foo' => 'bar'));
    }

}
