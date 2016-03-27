<?php

namespace sns\filter;

\Klass::bind(__NAMESPACE__ . '\Base', [
    'overviews' => function() {
        return ['sns/overview/fixed-header'];
    },
]);

trait Base {

    use \sns\filter\Login;
}
