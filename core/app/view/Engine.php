<?php

namespace app\view;

class Engine {

    use \feature\Accessable;

    protected $_path;
    protected $_dirs = [];

    public function __construct() {
        if (!ob_get_level()) ob_start();
    }

    public function includePath($paths) {
        $this->_dirs = array_merge($this->_dirs, (array)$paths);
        return $this;
    }

    public function currentPath($path) {
        $this->_path = $path;
        return $this;
    }

    public function render($path, $locals = []) {
        include $file = $this->find($path);
        array_unshift($this->_dirs, dirname($file));
        $this->compile($this->capture(), $locals, $file);
        array_shift($this->_dirs);
        return $this;
    }

    public function renderPartial($path, $locals = []) {
        ob_start();
        $this->render($path, $locals);
        return ob_get_clean();
    }

    public function renderOver($paths) {
        foreach (array_reverse($paths) as $path) {
            $this->_dirs[] = dirname($path);
            $this->yield = $this->capture();
            include $file = $this->find($path);
            $this->compile($this->capture(), [], $file);
            array_pop($this->_dirs);
        }
        return $this;
    }

    protected function compile($code, $locals = [], $__file__ = null) {
        extract($this->_data);
        extract(func_get_arg(1));
        eval('?' . '>' . Parser::parse(func_get_arg(0)));
    }

    protected function capture() {
        $yield = ob_get_contents();
        ob_clean();
        return $yield;
    }

    protected function find($path) {
        $path = preg_match('/\.\w+$/', $path) ?: "{$path}.html";

        foreach (array_merge([$this->_path], $this->_dirs) as $dir) {
            if ($file = stream_resolve_include_path("{$dir}/{$path}")) {
                if (is_file($file)) return $file;
            }
        }
        return $path;
    }
}
