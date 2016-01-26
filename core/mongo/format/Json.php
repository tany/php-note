<?php

namespace mongo\format;

trait Json {

    public static function decode($json) {
        if (!preg_match('/^\s*\{/s', $json)) $json = "{ {$json} }";
        $json = preg_replace('/,\s*\}\s*$/s', ' }', $json);
        $json = preg_replace('/([\'"])?([\w]+)([\'"])?: +/', '"$2": ', $json);
        $data = json_decode($json, true);
        if (!is_array($data)) throw new \Exception('Not json format');

        array_walk_recursive($data, 'self::decodeCallback');
        return $data;
    }

    protected static function decodeCallback(&$item, $key) {
        if (preg_match('/^\!<([^>]+?)> (.+)$/', $item, $m)) {
            if ($m[1] === 'ObjectID') {
                $item = new \MongoDB\BSON\ObjectID($m[2]);
            } elseif ($m[1] === 'Timestamp') {
                $item = new \MongoDB\BSON\Timestamp($m[2]);
            } elseif ($m[1] === 'UTCDateTime') {
                $item = new \MongoDB\BSON\UTCDateTime($m[2]);
            }
        }
    }

    public static function encode($data) {
        array_walk_recursive($data, 'self::encodeCallback');
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $json = preg_replace('/"([a-zA-Z_]+[\w]*)":/', '$1:', $json);
        return $json;
    }

    protected static function encodeCallback(&$val, $key) {
        if ($val instanceof \MongoDB\BSON\ObjectID) {
            $val = "!<ObjectID> {$val}";
        } elseif ($val instanceof \MongoDB\BSON\Timestamp) {
            $val = "!<Timestamp> {$val}";
        } elseif ($val instanceof \MongoDB\BSON\UTCDateTime) {
            $val = "!<UTCDateTime> {$val}";
        }
    }
}
