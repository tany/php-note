<?php

namespace app;

class Request {

    use \feature\Accessor;

    protected $_path;

    public function __construct($path = null) {
        $path = $path ?? $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'];
        $this->_path = preg_replace('/\?.*/', '', $path);
        $this->_data = $_REQUEST;
    }

    public function path() {
        return $this->_path;
    }

    public function method() {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function isGet() {
        return $this->method() === 'GET';
    }

    public function isPost() {
        return $this->method() === 'POST';
    }
}
