<?php

namespace app\asset\stylesheet;

class Engine {

    use Compiler;

    protected $path;
    protected $time;
    protected $data;
    protected $body;

    public function load($file) {
        $this->path = $file;
        $this->updateTime(filemtime($file));
        return $this->compile($this->loadFile($file));
    }

    public function compile($data) {
        $this->data = $data;
        $this->parse();
        return $this;
    }

    public function time() {
        return $this->time;
    }

    public function body() {
        if ($this->body) return $this->body;
        return $this->body = $this->buildCss();
    }

    protected function loadFile($file) {
        ob_start();
        include $file;
        return  ob_get_clean();
    }

    protected function updateTime($time) {
        $this->time = max($time, $this->time);
        return $this;
    }
}
