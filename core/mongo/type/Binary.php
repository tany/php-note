<?php

namespace mongo\type;

class Binary {

    public static function bsonExport($bson) {
        return base64_encode($bson->getData()) . ",{$bson->getType()}";
    }

    public static function bsonImport($str) {
        $val = explode(',', $str);
        return new \MongoDB\BSON\Binary(base64_decode($val[0]), $val[1]);
    }
}
