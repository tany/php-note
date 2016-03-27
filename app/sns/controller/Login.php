<?php

namespace sns\controller;

class Login extends \app\Controller {

    use \sns\filter\Base;

    public function login($request) {
        if (!$request->isPost()) return;

        if ($item = (new \sns\model\User)->authenticate($request->uid, $request->password)) {
            return $this->userLogin($item);
        }
    }

    public function logout($request) {
        return $this->userLogout();
    }

    public function join($request) {
        $item = (new \sns\model\User);
        $this->item = $item;

        if (!$request->isPost()) return;
        $item->setData($request->data);
        if ($item->save()) return $this->redirect("/login");
    }
}
