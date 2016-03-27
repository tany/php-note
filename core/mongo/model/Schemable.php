<?php

namespace mongo\model;

trait Schemable {

    protected static function allschema() {
        $schema = [];
        foreach (class_real_uses(get_called_class()) as $trait) {
            if (!function_exists($func = "{$trait}__schema")) continue;
            $schema[] = $func();
        }
        foreach (get_parent_classes(get_called_class()) as $class) {
            $schema[] = $class::schema();
        }
        $schema[] = static::schema();
        return array_merge(...$schema);
    }

    protected static function schema() {
        return [];
    }

    public static function fields() {
        return array_keys(self::allschema());
    }
}
