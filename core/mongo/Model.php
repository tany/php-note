<?php

namespace mongo;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use \MongoDB\BSON\Persistable;
use \MongoDB\BSON\Unserializable;

class Model implements Unserializable, \IteratorAggregate {

    use \feature\Accessable;
    use \feature\ClassBindable;
    use model\Schemable;
    use model\Findable;
    use model\Validatable;
    use model\Writable;
    use model\Formatable;

    protected $_conn;
    protected $_ns;
    protected $_saved;

    public function connect() {
        if ($this->_conn) return $this->_conn;
        return $this->_conn = \mongo\Connection::connect();
    }

    public function setNs($namespace) {
        $this->_ns = $namespace;
        return $this;
    }

    public function ns() {
        if ($this->_ns) return $this->_ns;
        return $this->_ns = "{$this->dbName()}." . $this->collectionName();
    }

    public function dbName() {
        if ($this->_ns) return substr($this->_ns, 0, strpos($this->_ns, '.'));
        return $this->connect()->dbName();
    }

    public function collectionName() {
        if ($this->_ns) return substr($this->_ns, strpos($this->_ns, '.') + 1);
        $name = str_replace('\\', '_', underscore(get_called_class()));
        return preg_replace('/_model/', '', $name, 1);
    }

    public function isNewDocument() {
        return empty($this->_saved);
    }

    public function bsonUnserialize(array $data) {
        $this->_saved = $data;
        $this->_data  = $data;
        $this->_conn  = \mongo\Connection::lastConnection();
        $this->_ns    = \mongo\Connection::lastQuery()['namespace'];

        array_walk_recursive($this->_data, function(&$item, $key) {
            if ($item instanceof \MongoDB\BSON\UTCDateTime) {
                $item = \mongo\type\DateTime::fromBSON($item);
            } elseif ($item instanceof \MongoDB\BSON\Timestamp) {
                $item = \mongo\type\Timestamp::fromBSON($item);
            }
        });
    }

    public function toBSON() {
        return array_map_recursive(function($item) {
            if (is_object($item) && $class = get_class($item)) {
                if ($item instanceof \MongoDB\BSON\Serializable) {
                    return $item->bsonSerialize();
                } elseif ($class === 'DateTime') {
                    return new \MongoDB\BSON\UTCDateTime($item->getTimeStamp() . '000');
                }
            }
            return $item;
        }, $this->_data);
    }
}
