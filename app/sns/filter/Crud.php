<?php

namespace sns\filter;

trait Crud {

    abstract protected function model();

    protected function params() {
        return $this->request->data ?? [];
    }

    public function index($request) {
        $this->items = $this->model()
            ->sort(['$natural' => -1])
            ->page($request->page, 20);
    }

    public function see($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;
    }

    public function create($request) {
        $item = $this->model();
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($request->data);
        if ($item->save()) return $this->redirect("./");
    }

    public function update($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($request->data);
        if ($item->save()) return $this->redirect("?");
    }

    public function delete($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        if ($item->remove()) return $this->redirect("./");
    }

    public function deleteAll($request) {
        foreach ($request->id as $id) {
            $item = $this->model()->find($id);
            $item->remove();
        }
        return $this->redirect("./");
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
