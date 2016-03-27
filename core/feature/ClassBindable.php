<?php

namespace feature;

trait ClassBindable {

    protected static function classBinds($name) {
        return \Klass::binds(get_called_class(), $name);
    }
}
