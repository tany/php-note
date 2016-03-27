<?php

namespace sns\controller;

class Main extends \app\Controller {

    use \sns\filter\Base;

    public function index($request) {
        $this->items = (new \note\model\Page)
            ->sort(['updated' => -1])
            ->page($request->page, 20);
    }
}
