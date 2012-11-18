<?php

namespace mikemeier\PHPNodeBridge\Store\Adapter;

use mikemeier\PHPNodeBridge\Store\StoreInterface;

abstract class PDOAdapter implements StoreInterface
{

    /**
     * @var \PDO
     */
    protected $connection = null;

    /**
     * @var string
     */
    protected $databaseName = null;

    /**
     * @var bool
     */
    protected $lazyLoading = true;

    /**
     * @var bool
     */
    protected $lazyStoring = false;

    /**
     * @var array
     */
    protected $cache = array();

    /**
     * @param resource $db
     * @param string $databaseName
     * @param bool $lazyLoading
     */
    public function __construct(\PDO $connection, $databaseName, $lazyLoading = true, $lazyStoring = false)
    {
        $this->connection = $connection;
        $this->databaseName = $databaseName;
        $this->lazyLoading = $lazyLoading;
        $this->lazyStoring = $lazyStoring;

        if(false === $lazyLoading){
            $this->loadFullDataSet();
        }
    }

    public function __destruct()
    {
        if(true === $this->lazyStoring){

        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if(false === $this->lazyLoading){
            return isset($this->cache[$key]) ? $this->cache[$key] : null;
        }

        $sql = "SELECT value FROM ". $this->databaseName ." WHERE key = :key LIMIT 1";
        $sth = $this->connection->prepare($sql);
        $sth->execute(array(':key' => $key));
        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        if($result && isset($result['value'])){
            return unserialize($result['value']);
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     * @return PDOAdapter|StoreInterface
     */
    public function set($key, $value)
    {
        if(true === $this->lazyStoring || true === $this->lazyLoading){
            $this->cache[$key] = $value;
        }

        return $this;
    }

    protected function loadFullDataSet()
    {
        $sql = "SELECT key, value FROM ". $this->databaseName;
        foreach($this->connection->query($sql) as $row){
            $this->cache[$row['key']] = unserialize($row['value']);
        }
    }

}