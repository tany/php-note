<?php

namespace sys\filter;

\Klass::bind(__NAMESPACE__ . '\Db', [
    'views' => function() {
        return ['sys/view/db/main'];
    },
    'overviews' => function() {
        return ['sns/overview/fixed-drawer'];
    },
]);

trait Db {
    //
}

// function Db__breadcrumbs() {
//     return function($request) {
//         //$this->overviews = ['sns/overview/fixed-header'];

//         $crumbs = [[$url = "/.sys/db/", 'Database']];

//         if ($db = $this->request->db) {
//             $crumbs[] = [$url = "{$url}{$db}/", $db];
//         }
//         if ($coll = $this->request->coll) {
//             $crumbs[] = [$url = "{$url}{$coll}/", $coll];
//         }
//         return $crumbs;
//     };
// }
