<?php

namespace feature;

trait Accessor {

    public $_data = [];

    public function __isset($key) {
        return isset($this->_data[$key]);
    }

    public function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function __set($key, $value) {
        $this->_data[$key] = $value;
    }

    public function __unset($key) {
        unset($this->_data[$key]);
    }

    public function data() {
        return $this->_data;
    }

    public function setData($data) {
        $this->_data = $data + $this->_data;
        return $this;
    }
}
