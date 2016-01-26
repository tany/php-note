<?php

namespace app\asset\javascript;

class Engine {

    use Compiler;

    protected $path;
    protected $time;
    protected $data;
    protected $body;

    public function load($file) {
        $this->path = $file;
        $this->data = $this->loadFile($file);
        return $this;
    }

    public function time() {
        return $this->time;
    }

    public function body() {
        if ($this->body) return $this->body;
        return $this->body = $this->compile($this->data);
    }

    protected function loadFile($file) {
        $this->updateTime(filemtime($file));
        ob_start();
        include $file;
        return  ob_get_clean();
    }

    protected function updateTime($time) {
        $this->time = max($time, $this->time);
        return $this;
    }
}
