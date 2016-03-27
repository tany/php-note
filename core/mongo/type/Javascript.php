<?php

namespace mongo\type;

class Javascript {

    public static function bsonExport($bson) {
        $code = print_r($bson, true);
        $code = preg_replace('/^.*?\[javascript\] => (.*)\)/s', '$1', $code);;
        return trim($code);
    }

    public static function bsonImport($str) {
        return new \MongoDB\BSON\Javascript($str);
    }
}
