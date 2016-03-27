<?php

namespace sns\filter;

\Klass::bind(__NAMESPACE__ . '\LoginRequired', [
    'before' => function($request) {
        return $this->requireLogin();
    },
]);

trait LoginRequired {
    //
}
