<?php

namespace sns\filter;

trait Base {

    protected function setUser($request) {
        $this->curUser = 1;
    }
}

class Base__Bind {

    public static function bindBefore() {
        return function($request) {
            $this->setUser($request);
        };
    }

    public static function bindOverviews() {
        return function($request) {
            $this->overviews = ['sns/overview/fixed-header'];
        };
    }
}
