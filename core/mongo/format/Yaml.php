<?php

namespace mongo\format;

class Yaml {

    public static function encode($data) {
        if (!$data) return null;
        $yaml = yaml_emit($data, YAML_UTF8_ENCODING, YAML_ANY_BREAK, [
            'mongo\type\DateTime'      => 'self::bsonUnserialize',
            'mongo\type\Timestamp'     => 'self::bsonUnserialize',
            'MongoDB\BSON\ObjectID'    => 'self::bsonUnserialize',
            'MongoDB\BSON\Binary'      => 'self::bsonUnserialize',
            'MongoDB\BSON\Javascript'  => 'self::bsonUnserialize',
            'MongoDB\BSON\Regex'       => 'self::bsonUnserialize',
            'MongoDB\BSON\MaxKey'      => 'self::bsonUnserialize',
            'MongoDB\BSON\MinKey'      => 'self::bsonUnserialize',
        ]);
        $yaml = preg_replace('/^---/', '', $yaml);
        $yaml = preg_replace('/\n\.\.\.$/', '', $yaml);
        return $yaml;
    }

    public static function decode($yaml) {
        $data = yaml_parse($yaml, 0, $ndocs, [
            'DateTime'   => 'self::bsonSerialize',
            'Timestamp'  => 'self::bsonSerialize',
            'ObjectID'   => 'self::bsonSerialize',
            'Binary'     => 'self::bsonSerialize',
            'Javascript' => 'self::bsonSerialize',
            'Regex'      => 'self::bsonSerialize',
            'MaxKey'     => 'self::bsonSerialize',
            'MinKey'     => 'self::bsonSerialize',
        ]);
        if (!is_array($data)) throw new \Exception('Not yaml format');
        return $data;
    }

    public static function bsonUnserialize($item) {
        if ($item instanceof \mongo\type\DateTime) {
            return ['tag' => 'DateTime', 'data' => $item->__toString()];
        } elseif ($item instanceof \mongo\type\Timestamp) {
            return ['tag' => 'Timestamp', 'data' => $item->__toString()];
        } elseif ($item instanceof \MongoDB\BSON\ObjectID) {
            return ['tag' => 'ObjectID', 'data' => $item->__toString()];
        } elseif ($item instanceof \MongoDB\BSON\Binary) {
            return ['tag' => 'Binary', 'data' =>  \mongo\type\Binary::bsonExport($item)];
        } elseif ($item instanceof \MongoDB\BSON\Javascript) {
            return ['tag' => 'Javascript', 'data' =>  \mongo\type\Javascript::bsonExport($item)];
        } elseif ($item instanceof \MongoDB\BSON\Regex) {
            return ['tag' => 'Regex', 'data' => \mongo\type\Regex::bsonExport($item)];
        } elseif ($item instanceof \MongoDB\BSON\MaxKey) {
            return ['tag' => 'MaxKey', 'data' => '$MaxKey'];
        } elseif ($item instanceof \MongoDB\BSON\MinKey) {
            return ['tag' => 'MinKey', 'data' => '$MinKey'];
        }
    }

    public static function bsonSerialize($item, $tag, $flags) {
        if ($tag === 'DateTime') {
            return \mongo\type\DateTime::fromString($item);
        } elseif ($tag === 'Timestamp') {
            return \mongo\type\Timestamp::fromString($item);
        } elseif ($tag === 'ObjectID') {
            return new \MongoDB\BSON\ObjectID($item);
        } elseif ($tag === 'Binary') {
            return \mongo\type\Binary::bsonImport($item);
        } elseif ($tag === 'Javascript') {
            return \mongo\type\Javascript::bsonImport($item);
        } elseif ($tag === 'Regex') {
            return \mongo\type\Regex::bsonImport($item);
        } elseif ($tag === 'MaxKey') {
            return new \MongoDB\BSON\MaxKey();
        } elseif ($tag === 'MinKey') {
            return new \MongoDB\BSON\MinKey();
        }
    }
}
