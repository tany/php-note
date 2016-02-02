<?php

namespace note\controller;

class Page extends \app\Controller {

    use \sns\filter\Base;
    use \sns\filter\Crud;

    protected function model() {
        return \note\Page::scope();
    }
}
