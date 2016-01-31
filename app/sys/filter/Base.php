<?php

namespace sys\filter;

trait Base {

    use \sns\filter\Base;

    protected function validSysUser($request) {
        //dump("valid system user");
    }
}

class Base__Bind {

    public static function bindBefore() {
        return function($request) {
            $this->validSysUser($request);
        };
    }

//     public static function breadcrumbs() {
//         return function($request) {
//             return [['/.sys/', 'Administration']];
//         };
//     }
}
