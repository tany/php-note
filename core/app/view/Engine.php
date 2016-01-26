<?php

namespace app\view;

class Engine {

    use \feature\Accessor;

    protected $dirs = [];

    public function __construct() {
        if (!ob_get_level()) ob_start();
    }

    public function includePath($paths) {
        $this->dirs = array_merge($this->dirs, (array)$paths);
        return $this;
    }

    public function render($path, $locals = []) {
        include $file = $this->find($path);
        $this->dirs[] = dirname($file);
        $this->compile($this->capture(), $locals);
        array_pop($this->dirs);
        return $this;
    }

    public function renderOver($paths) {
        foreach (array_reverse($paths) as $path) {
            array_unshift($this->dirs, dirname($path));
            $this->yield = $this->capture();
            include $file = $this->find($path);
            $this->compile($this->capture(), []);
            array_shift($this->dirs);
        }
        return $this;
    }

    public function renderPartial($path, $locals = []) {
        ob_start();
        $this->render($path, $locals);
        return ob_get_clean();
    }

    protected function compile($code, $locals = []) {
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

        foreach (array_reverse($this->dirs) as $dir) {
            if ($file = stream_resolve_include_path("{$dir}/{$path}")) {
                if (is_file($file)) return $file;
            }
        }
        return $path;
    }
}
