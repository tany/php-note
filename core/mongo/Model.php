<?php

namespace mongo;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use \MongoDB\BSON\Persistable;

class Model implements Persistable, \Iterator {

    use \feature\Accessor;
    use model\Findable;
    use model\Writable;
    use model\Format;

    protected $_cn;
    protected $_ns;

    public static function collection() {
        return str_replace('\\', '_', underscore(get_called_class()));
    }

    public function connect() {
        if ($this->_cn) return $this->_cn;
        return $this->_cn = \mongo\Connection::connect();
    }

    public function setNamespace($namespace) {
        $this->_ns = $namespace;
        return $this;
    }

    public function namespace() {
        if ($this->_ns) return $this->_ns;
        return $this->_ns = "{$this->connect()->db()}." . static::collection();
    }

    public function bsonSerialize() {
        throw new \Exception('?');
    }

    public function bsonUnserialize(array $data) {
        $this->_data = $data;
        $this->_cn = \mongo\Connection::lastConnection();
        $this->_ns = \mongo\Connection::lastQuery()['namespace'];
    }
}
