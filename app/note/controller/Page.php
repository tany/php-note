<?php

namespace note\controller;

class Page extends \app\Controller {

    use \sns\filter\Base;
    use \sns\filter\Crud;

    public function index() {
        ;
    }

    public function see() {
        $id = $this->request->id;
        $this->item = (new \note\Page)->find($id);
    }
}
