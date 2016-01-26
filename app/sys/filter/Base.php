<?php

namespace sys\filter;

trait Base {

    use \sns\filter\Base;

    protected function validSysUser($request) {
        //echo "valid system user";
    }
}

class Base__Bind {

    public static function before() {
        return function($request) {
            $this->validSysUser($request);
        };
    }

    public static function breadcrumbs() {
        return function($request) {
            return [['/.sys/', 'Administration']];
        };
    }
}
