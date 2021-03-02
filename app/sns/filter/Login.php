<?php

namespace sns\filter;

\Klass::bind(Login::class, 'before', function($request) {
    $this->isLogin();
});

trait Login {

    protected function isLogin() {
        if ($this->curUser) return $this->curUser;
        if ($data = $_COOKIE['login'] ?? null) {
            return $this->curUser = (new \sns\model\User)->sessionUnserialize($data);
        }
    }

    protected function requireLogin() {
        if (!$this->isLogin()) {
            return $this->redirect('/login');
        }
    }

    protected function userLogin($user) {
        setcookie('login', $user->sessionSerialize(), time() + 3600 * 24 * 7, '/');
        return $this->redirect('/');
    }

    protected function userLogout() {
        setcookie('login', '', time() - 3600, '/');
        return $this->redirect('/');
    }
}
