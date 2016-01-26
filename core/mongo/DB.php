<?php

namespace mongo;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\BulkWrite;

// https://github.com/mongodb/mongo-php-library/blob/master/src/Client.php
// https://github.com/mongodb/mongo-php-library/blob/master/src/Operation/ListDatabases.php

final class DB {

    public $result; // MongoDB\Driver\WriteResult

    protected static $lastConnection;
    protected static $lastQuery;
    protected $manager;
    protected $dbname;

    public function __construct($conf) {
        $this->manager = new Manager($conf['server'], $conf['options']);
        $this->dbname  = $conf['dbname'];
    }

    public static function connect($section = 'default') {
        static $caches = [];
        if (isset($caches[$section])) return $caches[$section];

        $conf = \app\Config::load('database', $section);
        return $caches[$section] = new self($conf);
    }

    public static function lastConnection() {
        return self::$lastConnection;
    }

    public static function lastQuery() {
        return self::$lastQuery;
    }

    public function command($db, $command) {
        return $this->manager->executeCommand($db, new Command($command));
    }

    public function adminCommand($command) {
        return $this->manager->executeCommand('admin', new Command($command));
    }

    public function databases() {
        $cursor = $this->adminCommand(['listDatabases' => 1]);
        $array  = current($cursor->toArray())->databases;
        usort($array, function($a, $b) { return $a->name <=> $b->name; });
        return $array;
    }

    public function collections($db) {
        $cursor = $this->command($db, ['listCollections' => 1]);
        $array  = $cursor->toArray();
        usort($array, function($a, $b) { return $a->name <=> $b->name; });
        return $array;
    }

    public function dropDatabase($db) {
        return $this->command($db, ['dropDatabase' => 1]);
    }

    public function dropCollection($db, $collection) {
        return $this->command($db, ['drop' => $collection]);
    }

    public function query($namespace, $filter, $options = []) {
        self::$lastConnection = $this;
        self::$lastQuery = ['namespace' => $namespace, 'filter' => $filter, 'options' => $options];
        return $this->manager->executeQuery($namespace, new Query($filter, $options));
    }

    public function write($namespace, $bulk) {
        return $this->manager->executeBulkWrite($namespace, $bulk);
    }

    public function insert($namespace, $data = []) {
        $bulk = new BulkWrite;
        $dataId = $bulk->insert($data);
        $this->result = $this->write($namespace, $bulk);
        return $this->result->getWriteErrors() ? false : $dataId;
    }

    public function update($namespace, $filter = [], $data = [], $options = []) {
        $bulk = new BulkWrite;
        $bulk->update($filter, $data, $options);
        $this->result = $this->write($namespace, $bulk);
        return !$this->result->getWriteErrors();
    }

    public function upsert($namespace, $filter = [], $data = [], $options = []) {
        $options['upsert'] = $options['upsert'] ?? true;
        return $this->update($namespace, $filter, $data, $options);
    }

    public function updateAll($namespace, $filter = [], $data = [], $options = []) {
        $options['multi'] = $options['multi'] ?? true;
        return $this->update($namespace, $filter, $data, $options);
    }

    public function delete($namespace, $filter = [], $options = []) {
        $bulk = new BulkWrite;
        $bulk->delete($filter, $options);
        $this->result = $this->write($namespace, $bulk);
        return !$this->result->getWriteErrors();
    }

    public function deleteAll($namespace, $filter = [], $options = []) {
        $options['limit'] = $options['limit'] ?? 0;
        return $this->delete($namespace, $filter, $options);
    }
}
