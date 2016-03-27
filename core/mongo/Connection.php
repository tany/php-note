<?php

namespace mongo;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\BulkWrite;

// https://github.com/mongodb/mongo-php-library/blob/master/src/Client.php
// https://github.com/mongodb/mongo-php-library/blob/master/src/Operation/ListDatabases.php

final class Connection {

    public $result; // MongoDB\Driver\WriteResult

    protected static $lastConnection;
    protected static $lastQuery;
    protected $manager;
    protected $dbName;

    public function __construct($conf) {
        $this->manager = new Manager($conf['server'], $conf['options']);
        $this->dbName  = $conf['dbname'];
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

    public function dbName() {
        return $this->dbName;
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

    public function query($namespace, $query, $options = []) {
        self::$lastConnection = $this;
        self::$lastQuery = ['namespace' => $namespace, 'query' => $query, 'options' => $options];
        return $this->manager->executeQuery($namespace, new Query($query, $options));
    }

    public function write($namespace, $bulk) {
        return $this->manager->executeBulkWrite($namespace, $bulk);
    }

    public function insert($namespace, $data = []) {
        $bulk = new BulkWrite;
        $dataId = $bulk->insert($data) ?? $data['_id'];
        $this->result = $this->write($namespace, $bulk);
        return $this->result->getWriteErrors() ? false : $dataId;
    }

    public function update($namespace, $query = [], $update = [], $options = []) {
        $bulk = new BulkWrite;
        $bulk->update($query, $update, $options);
        $this->result = $this->write($namespace, $bulk);
        return !$this->result->getWriteErrors();
    }

    public function upsert($namespace, $query = [], $update = [], $options = []) {
        $options['upsert'] = $options['upsert'] ?? true;
        return $this->update($namespace, $query, $update, $options);
    }

    public function updateAll($namespace, $query = [], $update = [], $options = []) {
        $options += ['multi' => true];
        return $this->update($namespace, $query, $update, $options);
    }

    public function remove($namespace, $query = [], $options = []) {
        $bulk = new BulkWrite;
        $bulk->delete($query, $options);
        $this->result = $this->write($namespace, $bulk);
        return !$this->result->getWriteErrors();
    }

    public function removeAll($namespace, $query = [], $options = []) {
        $options += ['limit' => 0];
        return $this->delete($namespace, $query, $options);
    }
}
