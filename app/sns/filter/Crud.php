<?php

namespace sns\filter;

\Klass::bind(Crud::class, 'views', function() {
    return ['sns/view/crud'];
});

trait Crud {

    abstract protected function model();

    public function index($request) {
        return $this->crudIndex($request);
    }

    public function see($request) {
        return $this->crudSee($request);
    }

    public function add($request) {
        return $this->crudAdd($request);
    }

    public function edit($request) {
        return $this->crudEdit($request);
    }

    public function drop($request) {
        return $this->crudDrop($request);
    }

    public function dropAll($request) {
        return $this->crudDropAll($request);
    }

    protected function params() {
        return $this->request->data ?? [];
    }

    protected function preParams() {
        return [];
    }

    protected function fixParams() {
        return [];
    }

    protected function crudIndex($request) {
        $this->items = $this->model()
            ->sort(['$natural' => -1])
            ->page($request->page, 20);
    }

    protected function crudSee($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;
    }

    protected function crudAdd($request) {
        $item = $this->model();
        $item->setData($this->preParams());
        $item->setData($this->fixParams());
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($this->params());
        $item->setData($this->fixParams());
        if ($item->save()) return $this->redirect("./");
    }

    protected function crudEdit($request) {
        $item = $this->model()->find($request->id);
        $item->setData($this->fixParams());
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($this->params());
        $item->setData($this->fixParams());
        if ($item->save()) return $this->redirect("?");
    }

    protected function crudDrop($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        if ($item->remove()) return $this->redirect("./");
    }

    protected function crudDropAll($request) {
        foreach ((array)$request->id as $id) {
            $item = $this->model()->find($id);
            $item->remove();
        }
        return $this->redirect("./");
    }
}
