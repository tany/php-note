<?php

namespace note\filter;

\Klass::bind(Base::class, 'overviews', function() {
    return ['note/overview/layout'];
});

trait Base {

    use \note\filter\Login;
}
