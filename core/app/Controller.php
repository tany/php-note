<?php

namespace app;

class Controller {

    use \feature\Accessor;
    //use controller\Renderer;

    protected $response;
    protected $views = [];
    protected $overviews = [];

    public function __construct($request, $controller, $action) {
        $this->request    = $request;
        $this->controller = $controller;
        $this->action     = $action;
        $this->response   = new Response;
    }

    public function __invoke() {
        $binders = [];
        foreach (class_uses_real(get_class($this)) as $trait) {
            if (class_exists($class = "{$trait}__Bind")) $binders[] = $class;
        }

        $binds = ['before', 'views', 'overviews'];
        foreach ($binders as $class) {
            if (!method_exists($class, 'binds')) continue;
            $binds = array_merge($binds, $class::binds()->call($this, $this->request));
        }

        foreach ($binds as $bind) {
            $generator = function() use ($binders, $bind) {
                foreach ($binders as $binder) {
                    if (!method_exists($binder, $bind)) continue;
                    yield $binder => $binder::$bind()->call($this, $this->request);
                }
            };
            $method = "bind" . ucfirst($bind);
            $this->$method($generator());
        }

        if ($resp = $this->{$this->action}($this->request)) {
            return $resp;
        }
        return $this->render();
    }

    protected function bindBefore($generator) {
        foreach ($generator as $views) {
            if ($this->resp);
        }
    }

    protected function bindViews($generator) {
        foreach ($generator as $views) {
            $this->views = array_merge($this->views, $views);
        }
    }

    protected function bindOverviews($generator) {
        foreach ($generator as $views) {
            if ($views) $this->overviews = $views;
        }
    }

    protected function render() {
        $action = $this->action;

        $view = new \app\view\Engine();
        $view->_data =& $this->_data;
        $view->includePath($this->views);
        $view->includePath(preg_replace('/\//', '/view/', underscore($this->controller), 1));
        $view->render($action);
        $view->renderOver($this->overviews);

        return $this->response->capture();
    }

    protected function redirect($url) {
        return $this->response->header("Location: {$url}");
    }
}
