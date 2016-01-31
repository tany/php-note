<?php

namespace sns\filter;

trait Crud {

    protected function setModel($request) {
    }

    protected function setItem($request) {
    }

    protected function params() {
        return $this->request->data ?? [];
    }

    public function index() {
        ;
    }

    public function see() {
        ;
    }

    public function create() {
        $item = new \db\Database($this->params());
        $this->item = $item;

        if (!$request->isPost()) return;
        if (!$item->save()) return;
        return $this->redirect('./');
    }

    public function update() {
        ;
    }

    public function delete() {
        ;
    }
}

class Crud__Bind {

    public static function bindBefore() {
        return function($request) {
            //$this->setModel($request);
            //$this->setItem($request);
        };
    }

    public static function bindViews() {
        return function($request) {
            $this->views[] = 'sns/view/crud';
        };
    }
}
