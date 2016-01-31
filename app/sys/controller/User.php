<?php

namespace sys\controller;

class User extends \app\Controller {

    use \sys\filter\Base;

    public function index() {
        $item = \sys\User::scope();
    }
}
