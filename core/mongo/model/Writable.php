<?php

namespace mongo\model;

trait Writable {

    public function remove() {
        return $this->connect()->delete($this->namespace(), ['_id' => $this->_id]);
    }
}
