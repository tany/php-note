<?php

namespace mongo;

class Util {

    public static function isObjectId($str) {
        return (($len = strlen($str)) === 12 || $len === 24) && preg_match("/^[0-9a-f]+$/", $str);
    }
}
