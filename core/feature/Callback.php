<?php

namespace feature;

trait Callback {

    protected function callbacks($name) {
        static $methods = [];
        if (isset($methods[$name])) return $methods[$name];

        $names = [];
        foreach (array_reverse(class_parents($this)) as $c) {
            foreach ($c::$$name as $key => $val) {
                if (is_int($key)) $names[$val] = 1;
                elseif ($val === 0) $names[$key] = 0;
            }
        }
        foreach (static::$$name as $key => $val) {
            if (is_int($key)) $names[$val] = 1;
            elseif ($val === 0) $names[$key] = 0;
        }
        return $methods[$name] = array_keys(array_filter($names));
    }
}
