<?php

namespace app\asset\javascript;

class Router {

    public static function run($request) {
        $path = $request->path();
        if (!preg_match('/\.js$/', $path)) return null;

        $file = WEB . preg_replace('/(\.js)$/', '.php$1', $path);
        if (!is_file($file)) return null;

        self::includePath(ASSET);

        $engine = new Engine;
        $engine->load($file);

        $resp = new \app\Response;
        $resp->setType($request->type ?? 'text/javascript');

        //if ($resp->cache($engine->time())) return $resp;
        return $resp->setBody($engine->body());
    }

    protected static function includePath(...$paths) {
        \app\Loader::includePath(...$paths);
    }
}
