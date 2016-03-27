<?php

namespace app\controller;

class Router {

    public static $routes = [];

    protected static $conf  = APP . '/*/conf/routes.php';
    protected static $cache = TMP . '/routes.cache';

    public static function run($request) {
        if (!self::$routes) self::load();
        if (!$route = self::match($request)) return;

        return (new $route['class']($request, $route['controller'], $route['action']))();
    }

    protected static function load() {
        $time = filemtime(__FILE__);
        foreach ($files = glob(self::$conf) as $f) {
            $time = max($time, filemtime($f));
        }

        if (($cache = apcu_fetch('app.routes'))[0] >= $time) {
            self::$routes = $cache[1];
        } else {
            $conf = '';
            foreach ($files as $f) $conf .= load_file($f) . "\n";
            apcu_store('app.routes', [$time, self::$routes = self::parse($conf)]);
        }
    }

    protected static function parse($conf) {
        $routes = [];
        $curpos = [];

        foreach (explode("\n", $conf) as $line) {
            if (($pos = strpos($line, '#')) !== false) $line = substr($line, 0, $pos);
            if (!$line = rtrim($line)) continue;

            $depth = strlen(preg_filter('/^(\s+).*/', '$1', $line));

            $data = preg_split('/\s+/', ltrim($line));
            $path = $data[0];
            $node = $data[1] ?? null;

            $curpos = array_slice($curpos, 0, $depth);
            $curpos = array_pad($curpos, $depth, '');
            $curpos[] = $path;

            if (!$node) continue;

            $line = preg_replace('/^\s*.*?\s+/', '', $line);
            $path = preg_replace('/\/+/', '/', '/' . join('/', $curpos));
            $node = preg_replace('/\s*/', '', $line);

            $patt = str_replace('.', '\.', $path);
            $patt = preg_replace('/:\w+/', '([^/]+)', $patt);
            $patt = preg_replace('/@\w+/', '(\d+)', $patt);

            $class = preg_replace('/\//', '\\controller\\', dirname($node), 1);
            $class = preg_replace('/\//', '\\', $class);

            $routes[] = [
                'path' => $path,
                'controller' => dirname($node),
                'action' => basename($node),
                'pattern' => $patt,
                'class' => $class,
            ];
        }
        return $routes;
    }

    protected static function match($request) {
        $requestPath = $request->path();

        foreach (self::$routes as $route) {
            if (!preg_match("|^{$route['pattern']}$|", $requestPath, $m)) {
                continue;
            }
            if (preg_match_all('/[:\*]+(\w+)/', $route['path'], $keys)) {
                foreach ($keys[1] as $i => $name) $request->$name = $m[$i + 1];
            }
            if ($request->do) {
                $route['action'] = $request->do;
            }
            return $route;
        }
    }
}
