<?php

namespace mongo\model;

trait Findable {

    protected $_filter  = [];
    protected $_options = [];

    public static function scope() {
        return new static;
    }

    public function where($filter) {
        $this->_filter += $filter;
        $this->_documents = null;
        return $this;
    }

    public function sort($sort) {
        $this->_options['sort'] = $sort;
        $this->_documents = null;
        return $this;
    }

    public function size() {
        list($db, $coll) = preg_split('/\./', $this->_ns, 2);

        $command = ['count' => $coll, 'query' => $this->_filter];
        // limit, skip

        $cursor = $this->db()->command($db, $command);
        return current($cursor->toArray())->n;
    }

    public function all() {
        $cursor = $this->db()->query($this->_ns, $this->_filter, $this->_options);
        $cursor->setTypeMap(['root' => get_class($this)]);
        return $cursor->toArray();
    }

    public function one() {
        $options = $this->_options + ['limit' => 1];
        $cursor = $this->db()->query($this->_ns, $this->_filter, $options);
        $cursor->setTypeMap(['root' => get_class($this)]);
        return current($cursor->toArray());
    }

    public function find($id) {
        if (is_string($id)) {
            if (\mongo\Util::isObjectId($id)) {
                $id = new \MongoDB\BSON\ObjectID($id);
            } elseif (is_numeric($id)) {
                $id = (int)$id;
            }
        }
        $filter = $this->_filter + ['_id' => $id];
        $cursor = $this->db()->query($this->_ns, $filter, $this->_options);
        $cursor->setTypeMap(['root' => get_class($this)]);
        return current($cursor->toArray());
    }
}
