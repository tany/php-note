<?php

namespace app\util;

class Password {

    public static function hash($str) {
        return password_hash($str, PASSWORD_DEFAULT);
    }

    public static function verify($str, $hash) {
        return password_verify($str, $hash);
    }
}
