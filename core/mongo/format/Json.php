<?php

namespace mongo\format;

class Json {

    public static function encode($data) {
        if (!$data) return null;
        array_walk_recursive($data, 'self::bsonUnserialize');
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        //$json = preg_replace('/"([a-zA-Z_]+[\w]*)":/', '$1:', $json);
        return $json;
    }

    public static function decode($json) {
        if (!preg_match('/^\s*\{/s', $json)) $json = "{ {$json} }";
        $json = preg_replace('/,\s*\}\s*$/s', ' }', $json);
        //$json = preg_replace('/([\'"])?([\w]+)([\'"])?: +/', '"$2": ', $json);
        $data = json_decode($json, true);
        if (!is_array($data)) throw new \Exception('Not json format');

        array_walk_recursive($data, 'self::bsonSerialize');
        return $data;
    }

    public static function bsonUnserialize(&$item, $key) {
        if ($code = Yaml::bsonUnserialize($item)) {
            $item = "!<{$code['tag']}> {$code['data']}";
        }
    }

    public static function bsonSerialize(&$item, $key) {
        if (!is_string($item)) return;
        if (!preg_match('/^\!<([^>]+?)> (.+)$/', $item, $m)) return;
        if ($serialized = Yaml::bsonSerialize($m[2], $m[1], 1)) {
            $item = $serialized;
        }
    }
}
