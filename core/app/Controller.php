<?php

namespace app;

class Controller {

    use \feature\Accessable;
    use \feature\ClassBindable;

    protected $response;

    public function __construct($request, $controller, $action) {
        $this->request    = $request;
        $this->controller = $controller;
        $this->action     = $action;
        $this->response   = new Response;
    }

    public function __invoke() {
        $request = $this->request;

        foreach ($this->classBinds('before') as $func) {
            if ($resp = $func->call($this, $request)) return $resp;
        }
        if ($resp = $this->before($request)) return $resp;
        if ($resp = $this->{$this->action}($request)) return $resp;

        return $this->render();
    }

    protected function before($request) {
        //
    }

    protected function views() {
        return [];
    }

    protected function overviews() {
        return [];
    }

    protected function bindViews() {
        $paths = [];
        foreach ($this->classBinds('views') as $func) {
            $paths = array_merge($paths, $func->call($this));
        }
        return array_merge($paths, $this->views());
    }

    protected function bindOverviews() {
        if ($paths = $this->overviews()) return $paths;
        foreach (array_reverse($this->classBinds('overviews')) as $func) {
            if ($paths = $func->call($this)) return $paths;
        }
        return [];
    }

    protected function render($action = null) {
        $request = $this->request;
        $action  = $action ?? $this->action;

        $view = new \app\view\Engine;
        $view->_data =& $this->_data;
        $view->includePath($this->bindViews());
        $view->currentPath(preg_replace('/\//', '/view/', underscore($this->controller), 1));
        $view->render($action);
        $view->renderOver($this->bindOverviews());

        return $this->response->capture();
    }

    protected function redirect($url) {
        return $this->response->header("Location: {$url}");
    }
}
