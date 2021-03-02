<?php

namespace note\controller;

class Pages extends \app\Controller {

    use \note\filter\Base;
    use \crud\filter\Base;

    protected function fixParams() {
        return ['uid' => $this->curUser->uid];
    }

    protected function before($request) {
        if (!preg_match('/index|see/', $this->action)) {
            return $this->requireLogin();
        }
    }

    protected function model() {
        return \note\model\Page::scope();
    }

    public function index($request) {
        $this->items = $this->model()
            ->sort(['updated' => -1])
            ->page($request->page, 20);
    }
}
