<?php

namespace mongo\type;

class Regex {

    public static function bsonExport($bson) {
        return "/{$bson->getPattern()}/{$bson->getFlags()}";
    }

    public static function bsonImport($str) {
        $pos = strrpos($str, '/');
        $flags = substr($str, $pos + 1);
        $pattern = substr($str, 1, $pos - 1);
        return new \MongoDB\BSON\Regex($pattern, $flags);
    }
}
