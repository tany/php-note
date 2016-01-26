<?php

namespace app;

class Dispatcher {

    public static function run($request = null) {
        $request = $request ?? new Request();

        if ($resp = asset\stylesheet\Router::run($request)) {
            return $resp->send();
        } elseif ($resp = asset\javascript\Router::run($request)) {
            return $resp->send();
        } elseif ($resp = controller\Router::run($request)) {
            return $resp->send();
        }
        return self::except()->send();
    }

    protected static function except() {
        echo '<h1>Route not found.</h2>';

        foreach (controller\Router::$routes as $route) {
            echo "<div>{$route['path']}</div>\n";
        }
        return (new Response)->setCode(200)->capture();
    }
}
