<?php

namespace note\filter;

\Klass::bind(LoginRequired::class, 'before', function($request) {
    return $this->requireLogin();
});

trait LoginRequired {
    //
}
