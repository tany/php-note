<?php

namespace mongo\model;

trait Writable {

    public function save() {
        if (isset($this->_id)) {
            return $this->connect()->upsert($this->namespace(), ['_id' => $this->_id], $this->_data);
        }
        $this->_id = $this->connect()->insert($this->namespace(), $this->_data);
        return $this->_id;
    }

    public function remove() {
        return $this->connect()->delete($this->namespace(), ['_id' => $this->_id]);
    }
}
