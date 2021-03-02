<?php

namespace crud\filter;

\Klass::bind(Base::class, 'views', function() {
    return ['crud/view/resources'];
});

trait Base {

    abstract protected function model();

    protected function params() {
        return $this->request->data ?? [];
    }

    protected function preParams() {
        return [];
    }

    protected function fixParams() {
        return [];
    }

    public function index($request) {
        return $this->crudIndex($request);
    }

    public function see($request) {
        return $this->crudSee($request);
    }

    public function create($request) {
        return $this->crudCreate($request);
    }

    public function update($request) {
        return $this->crudUpdate($request);
    }

    public function delete($request) {
        return $this->crudDelete($request);
    }

    public function deleteAll($request) {
        return $this->crudDeleteAll($request);
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

    protected function crudCreate($request) {
        $item = $this->model();
        $item->setData($this->preParams());
        $item->setData($this->fixParams());
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($this->params());
        $item->setData($this->fixParams());
        if ($item->save()) return $this->redirect("./");
    }

    protected function crudUpdate($request) {
        $item = $this->model()->find($request->id);
        $item->setData($this->fixParams());
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($this->params());
        $item->setData($this->fixParams());
        if ($item->save()) return $this->redirect("?");
    }

    protected function crudDelete($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        if ($item->remove()) return $this->redirect("./");
    }

    protected function crudDeleteAll($request) {
        foreach ((array)$request->id as $id) {
            $item = $this->model()->find($id);
            $item->remove();
        }
        return $this->redirect("./");
    }
}
