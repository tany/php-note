<?php

namespace app;

class Controller {

    use \feature\Accessor;

    protected $response;
    protected $views     = [];
    protected $overviews = [];

    public function __construct($request, $controller, $action) {
        $this->request    = $request;
        $this->controller = $controller;
        $this->action     = $action;
        $this->response   = new Response;
    }

    public function __invoke() {
        $binders = [];
        foreach (class_uses_real(get_called_class()) as $trait) {
            if (class_exists($class = "{$trait}__Bind")) $binders[] = $class;
        }

        $binds = ['before', 'views', 'overviews'];
        foreach ($binds as $name) {
            $method = 'bind' . ucfirst($name);
            foreach ($binders as $class) {
                if (method_exists($class, $method)) $class::$method()->call($this, $this->request);
            }
            $this->$name($this->request);
        }

        if ($resp = $this->{$this->action}($this->request)) {
            return $resp;
        }
        return $this->render();
    }

    protected function before($request) {
        //
    }

    protected function views($request) {
        //
    }

    protected function overviews($request) {
        //
    }

    protected function render($action = null) {
        $action = $action ?? $this->action;

        $view = new \app\view\Engine();
        $view->_data =& $this->_data;
        $view->includePath($this->views);
        $view->currentPath(preg_replace('/\//', '/view/', underscore($this->controller), 1));
        $view->render($action);
        $view->renderOver($this->overviews);

        return $this->response->capture();
    }

    protected function redirect($url) {
        return $this->response->header("Location: {$url}");
    }
}
