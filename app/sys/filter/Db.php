<?php

namespace sys\filter;

trait Db {
}

class Db__Bind {

    public static function overviews() {
        return function($request) {
            return ['sns/overview/fixed-drawer'];
        };
    }

    public static function views() {
        return function($request) {
            return ['sys/view/db/main'];
        };
    }

    public static function breadcrumbs() {
        return function($request) {
            $crumbs = [[$url = "/.sys/db/", 'Database']];

            if ($db = $this->request->db) {
                $crumbs[] = [$url = "{$url}{$db}/", $db];
            }
            if ($coll = $this->request->coll) {
                $crumbs[] = [$url = "{$url}{$coll}/", $coll];
            }
            return $crumbs;
        };
    }
}
