<?php

namespace mongo\model;

trait Findable {

    protected $_cursor;
    protected $_query   = [];
    protected $_options = [];

    public static function scope() {
        return new static;
    }

    public function getIterator() {
        if ($this->_cursor) return $this->_cursor;
        return $this->_cursor = $this->all();
    }

    public function reset() {
        $this->_cursor = null;
        return $this;
    }

    public function where($query) {
        $this->_query += $query;
        return $this->reset();
    }

    public function sort($sort) {
        $this->_options['sort'] = $sort;
        return $this->reset();
    }

    public function skip($num) {
        $this->_options['skip'] = $num;
        return $this->reset();
    }

    public function limit($num) {
        $this->_options['limit'] = $num;
        return $this->reset();
    }

    public function page($page, $limit = 20) {
        $page = $page ?? 1;
        $this->_options['page'] = $page;
        $this->_options['skip'] = (($page ?? 1) - 1) * $limit;
        $this->_options['limit'] = $limit;
        return $this->reset();
    }

    public function size() {
        $command = ['count' => $this->collectionName(), 'query' => $this->_query];
        $cursor = $this->connect()->command($this->dbName(), $command);
        return current($cursor->toArray())->n;
    }

    public function paginate() {
        return (new \app\html\Pagination())
            ->size($this->size())
            ->page($this->_options['page'])
            ->limit($this->_options['limit']);
    }

    public function all() {
        $cursor = $this->connect()->query($this->ns(), $this->_query, $this->_options);
        $cursor->setTypeMap(['root' => get_called_class(), 'document' => 'array']);
        return new Cursor($cursor->toArray());
    }

    public function one() {
        $options = ['limit' => 1] + $this->_options;
        $cursor = $this->connect()->query($this->ns(), $this->_query, $options);
        $cursor->setTypeMap(['root' => get_called_class(), 'document' => 'array']);
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
        $filter = ['_id' => $id] + $this->_query;
        $cursor = $this->connect()->query($this->ns(), $filter, $this->_options);
        $cursor->setTypeMap(['root' => get_called_class(), 'document' => 'array']);
        return current($cursor->toArray());
    }
}
