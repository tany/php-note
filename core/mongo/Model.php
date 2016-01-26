<?php

namespace mongo;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;

class Model implements \MongoDB\BSON\Persistable, \Iterator {

    use \feature\Accessor;
    use \mongo\model\Iterator;
    use \mongo\model\Findable;
    use \mongo\model\Format;

    protected $_db;
    protected $_ns;

    public function bsonSerialize() {
        throw new \Exception('?');
    }

    public function bsonUnserialize(array $data) {
        $this->_data = $data;
        $this->_db = \mongo\DB::lastConnection();
        $this->_ns = \mongo\DB::lastQuery()['namespace'];
    }

    public function db() {
        if ($this->_db) return $this->_db;
        return $this->_db = \mongo\DB::connect();
    }

    public function setDB($db) {
        $this->_db = $db;
        return $this;
    }

    public function ns() {
        return $this->_ns;
    }

    public function setNS($namespace) {
        $this->_ns = $namespace;
        return $this;
    }

    // writable

    public function remove() {
        return $this->db()->delete($this->_ns, ['_id' => $this->_id]);
    }
}
