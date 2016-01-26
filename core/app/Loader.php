<?php

namespace app;

class Loader {

    public static function includePath(...$paths) {
        set_include_path(get_include_path() . PATH_SEPARATOR . join(PATH_SEPARATOR, $paths));
    }

    public static function register() {
        spl_autoload_register('self::loadClass');
    }

    protected static function loadClass($class) {
        $file = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
        if ($file = stream_resolve_include_path($file)) require $file;
    }
}
