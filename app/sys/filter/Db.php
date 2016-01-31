<?php

namespace sys\filter;

trait Db {
}

class Db__Bind {

    public static function bindViews() {
        return function($request) {
            $this->views[] = 'sys/view/db/main';
        };
    }

    public static function bindOverviews() {
        return function($request) {
            $this->overviews = ['sns/overview/fixed-drawer'];
        };
    }

//     public static function breadcrumbs() {
//         return function($request) {
//             //$this->overviews = ['sns/overview/fixed-header'];

//             $crumbs = [[$url = "/.sys/db/", 'Database']];

//             if ($db = $this->request->db) {
//                 $crumbs[] = [$url = "{$url}{$db}/", $db];
//             }
//             if ($coll = $this->request->coll) {
//                 $crumbs[] = [$url = "{$url}{$coll}/", $coll];
//             }
//             return $crumbs;
//         };
//     }
}
