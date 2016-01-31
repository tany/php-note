<?php

namespace mongo\model;

trait Findable {

    protected $_filter  = [];
    protected $_options = [];
    protected $_cursor;

    public static function scope() {
        return new static;
    }

    public function rewind() {
        if (!$this->_cursor) $this->_cursor = $this->all();
        return reset($this->_cursor);
    }

    public function current() {
        return current($this->_cursor);
    }

    public function key() {
        return key($this->_cursor);
    }

    public function next() {
        return next($this->_cursor);
    }

    public function valid() {
        return key($this->_cursor) !== null;
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

    public function skip($num) {
        $this->_options['skip'] = $num;
        return $this;
    }

    public function limit($num) {
        $this->_options['limit'] = $num;
        return $this;
    }

    public function page($page, $limit = null) {
        $this->_page = $page ?? 1;
        $this->_options['skip'] = (($page ?? 1) - 1) * $limit;
        $this->_options['limit'] = $limit ?? $this->_options['limit'] ?? 50;
        return $this;
    }

    public function size() {
        list($db, $coll) = preg_split('/\./', $this->namespace(), 2);

        $command = ['count' => $coll, 'query' => $this->_filter];
        // limit, skip

        $cursor = $this->connect()->command($db, $command);
        return current($cursor->toArray())->n;
    }

    public function paginate() {
        return (new \html\Pagination())
            ->size($this->size())
            ->page($this->_page)
            ->limit($this->_options['limit']);
    }

    public function all() {
        $cursor = $this->connect()->query($this->namespace(), $this->_filter, $this->_options);
        $cursor->setTypeMap(['root' => get_called_class()]);
        return $cursor->toArray();
    }

    public function one() {
        $options = $this->_options + ['limit' => 1];
        $cursor = $this->connect()->query($this->namespace(), $this->_filter, $options);
        $cursor->setTypeMap(['root' => get_called_class()]);
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
        $cursor = $this->connect()->query($this->namespace(), $filter, $this->_options);
        $cursor->setTypeMap(['root' => get_called_class()]);
        return current($cursor->toArray());
    }
}
