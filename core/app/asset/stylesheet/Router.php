<?php

namespace app\asset\stylesheet;

class Router {

    public static function run($request) {
        $path = $request->path();
        if (!preg_match('/\.css$/', $path)) return null;

        $file = WEB . preg_replace('/(\.css)$/', '.php$1', $path);
        if (!is_file($file)) return null;

        self::includePath(ASSET);

        $engine = new Engine;
        $engine->load($file);

        $resp = new \app\Response;
        $resp->setType($request->type ?? 'text/css');

        //if ($resp->cache($engine->time())) return $resp;
        return $resp->setBody($engine->body());
    }

    protected static function includePath(...$paths) {
        \app\Loader::includePath(...$paths);
    }
}
