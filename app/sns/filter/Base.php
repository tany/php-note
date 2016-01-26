<?php

namespace sns\filter;

trait Base {

    protected function setUser($request) {
        $this->curUser = 1;
    }

    protected function bindBreadcrumbs($generator) {
        $this->breadcrumbs = [];
        foreach ($generator as $links) {
            $this->breadcrumbs = array_merge($this->breadcrumbs, $links);
        }
    }
}

class Base__Bind {

    public static function binds() {
        return function($request) {
            return ['breadcrumbs'];
        };
    }

    public static function before() {
        return function($request) {
            $this->setUser($request);
        };
    }

    public static function overviews() {
        return function($request) {
            return ['sns/overview/fixed-header'];
        };
    }
}
