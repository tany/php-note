<?php

namespace mongo\format;

trait Yaml {

    public static function decode($yaml) {
        $data = yaml_parse($yaml, 0, $ndocs, [
            'ObjectID'    => 'self::decodeCallback_ObjectID',
            'Timestamp'   => 'self::decodeCallback_Timestamp',
            'UTCDateTime' => 'self::decodeCallback_UTCDateTime',
        ]);
        if (!is_array($data)) throw new \Exception('Not yaml format');
        return $data;
    }

    protected static function decodeCallback_ObjectID($value, $tag, $flags) {
        return new \MongoDB\BSON\ObjectID($value);
    }

    protected static function decodeCallback_Timestamp($value, $tag, $flags) {
        $sec = preg_split('/:/', trim($value, '[]'));
        return new \MongoDB\BSON\Timestamp($sec[0], $sec[1]);
    }

    protected static function decodeCallback_UTCDateTime($value, $tag, $flags) {
        return new \MongoDB\BSON\UTCDateTime($value);
    }

    public static function encode($data) {
        $yaml = yaml_emit($data, YAML_UTF8_ENCODING, YAML_ANY_BREAK, [
            'MongoDB\BSON\ObjectID'    => 'self::encodeCallback',
            'MongoDB\BSON\Timestamp'   => 'self::encodeCallback',
            'MongoDB\BSON\UTCDateTime' => 'self::encodeCallback',
        ]);
        $yaml = preg_replace('/^---/', '', $yaml);
        $yaml = preg_replace('/\n\.\.\.$/', '', $yaml);
        return $yaml;
    }

    protected static function encodeCallback($obj) {
        $tag = preg_replace('/.*\\\/', '', get_class($obj));
        return ['tag' => $tag, 'data' => (string)$obj];
    }
}
