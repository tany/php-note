<?php

namespace sys\controller;

class User extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Crud;

    protected function model() {
        return \sys\User::scope();
    }
}
