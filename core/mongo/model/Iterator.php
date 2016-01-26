<?php

namespace mongo\model;

trait Iterator {

    protected $_documents;

    function rewind() {
        if (!$this->_documents) $this->_documents = $this->all();
        return reset($this->_documents);
    }

    function current() {
        return current($this->_documents);
    }

    function key() {
        return key($this->_documents);
    }

    function next() {
        return next($this->_documents);
    }

    function valid() {
        return key($this->_documents) !== null;
    }
}
