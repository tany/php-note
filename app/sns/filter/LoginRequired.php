<?php

namespace sns\filter;

\Klass::bind(LoginRequired::class, 'before', function($request) {
    return $this->requireLogin();
});

trait LoginRequired {
    //
}
