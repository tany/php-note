<?php

namespace app;

class Config {

    public static function load($name, $section = 'default') {
        static $caches = [];
        if (isset($caches[$key = "{$name}--{$section}"])) return $caches[$key];

        $conf = yaml_parse(load_file(ROOT . "/conf/default/{$name}.yml"))[$section];
        if (is_file($file = ROOT . "/conf/{$name}.yml")) {
            $conf = array_replace_recursive($conf, yaml_parse(load_file($file))[$section]);
        }
        return $caches[$key] = $conf;
    }
}
