<?php

namespace app\asset\javascript;

trait Compiler {

    public function compile($data = null) {
        return $this->parse($data);
    }

    public function parse($data) {
        $data = $this->parseRequire($data, $this->path); //= require
        return $data;
    }

    protected function parseRequire($data, $path) {
        return preg_replace_callback('/\/\/= require (.*)$/m', function($m) use ($path) {
            $file = trim($m[1], '"\'');
            $file = stream_resolve_include_path($file);
            return $this->parseRequire($this->loadFile($file), $file);
        }, $data);
    }
}
