<?php

namespace app\asset\stylesheet;;

class Mixin {

    public static function call($name, $value) {
        $method = str_replace('-', '_', $name);

        if (method_exists(__CLASS__, $method)) return self::$method($value);
        return self::css3($name, $value);
    }

    protected static function clear() {
        return 'zoom: 1;<s name=":before "> display: table; content: ""; </s>' .
            '<s name=":after "> display: table; clear: both; content: " "; </s>';
    }

    protected static function css3($name, $value) {
        return "{$name}: {$value};" .
            " -o-{$name}: {$value};" .
            " -ms-{$name}: {$value};" .
            " -moz-{$name}: {$value};" .
            " -webkit-{$name}: {$value};";
    }

    protected static function background_clip($value) {
        return "background-clip: {$value}-box;" .
            " -o-background-clip: {$value}-box;" .
            " -ms-background-clip: {$value}-box;" .
            " -moz-background-clip: " . preg_replace('/-box$/', '', $value) . ";" .
            " -webkit-background-clip: {$value}-box;";
    }

    protected static function background_origin($value) {
        return "background-origin: {$value}-box;" .
            " -o-background-origin: {$value}-box;" .
            " -ms-background-origin: {$value}-box;" .
            " -moz-background-origin: " . preg_replace('/-box$/', '', $value) . ";" .
            " -webkit-background-origin: {$value}-box;";
    }

    protected static function linear_gradient($value) {
        return "background: linear-gradient({$value});" .
            " background: -o-linear-gradient({$value});" .
            " background: -ms-linear-gradient({$value});" .
            " background: -moz-linear-gradient({$value});" .
            " background: -webkit-linear-gradient({$value});";
    }

    protected static function radial_gradient($value) {
        return "background: radial-gradient({$value});" .
            " background: -o-radial-gradient({$value});" .
            " background: -ms-radial-gradient({$value});" .
            " background: -moz-radial-gradient({$value});" .
            " background: -webkit-radial-gradient({$value});";
    }
}
