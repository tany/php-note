<?php

class Klass {

    protected static $binds = [];

    public static function bind($class, $name, $closure) {
        self::$binds["{$class}::{$name}"] = $closure;
    }

    public static function binds($class, $name) {
        static $cache;
        if (isset($cache["{$class}::{$name}"])) return $cache["{$class}::{$name}"];

        $binds = [];
        foreach (class_real_uses($class) as $trait) {
            if (isset(self::$binds["{$trait}::{$name}"])) $binds[] = self::$binds["{$trait}::{$name}"];
        }
        return $cache["{$class}::{$name}"] = $binds;
    }
}
