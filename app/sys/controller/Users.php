<?php

namespace sys\controller;

class Users extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Crud;

    protected function model() {
        return \sns\model\User::scope();
    }
}
