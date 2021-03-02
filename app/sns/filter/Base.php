<?php

namespace sns\filter;

\Klass::bind(Base::class, 'overviews', function() {
    return ['sns/overview/fixed-header'];
});

trait Base {

    use \sns\filter\Login;
}
