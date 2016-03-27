<?php

namespace sys\model;

class Document extends \mongo\Model {

    public function cssClass($field) {
        return is_string($this->$field) ? 'field-string' : 'field-non-string';
    }

    public function fieldType($field) {
        $item = $this->$field;
        $type = gettype($item);
        return ($type === 'object') ? preg_replace('/.*\\\/', '', get_class($item)) : ucfirst($type);
    }

    public function toShortString($field) {
        $item = $this->$field ?? null;

        if ($item instanceof \mongo\type\Timestamp) {
            return $item->format('Y/n/j H:i');
        } elseif ($item instanceof \mongo\type\DateTime) {
            return $item->format('Y/n/j H:i');
        } elseif ($item instanceof \MongoDB\BSON\ObjectId) {
            return $item->__toString();
        } else {
            return mb_strimwidth($this->toString($field), 0, 16);
        }
    }

    public function toString($field) {
        $item = $this->$field ?? null;

        if (!array_key_exists($field, $this->_data)) {
            return null;
        } elseif (is_null($item)) {
            return var_export($item, true);
        } elseif (is_bool($item)) {
            return var_export($item, true);
        } elseif (is_string($item)) {
            return $item;
        } elseif (is_numeric($item)) {
            return $item;
        } elseif (is_array($item)) {
            return \mongo\format\JSON::encode($item);
        } elseif ($item instanceof \mongo\type\Timestamp) {
            return $item->__toString();
        } elseif ($item instanceof \mongo\type\DateTime) {
            return $item->__toString();
        } elseif ($item instanceof \MongoDB\BSON\ObjectId) {
            return $item->__toString();
        } elseif ($item instanceof \MongoDB\BSON\Binary) {
            return pretty_size(strlen($item->getData()));
        } elseif ($item instanceof \MongoDB\BSON\Javascript) {
            return \mongo\type\Javascript::bsonExport($item);
        } elseif ($item instanceof \MongoDB\BSON\Regex) {
            return \mongo\type\Regex::bsonExport($item);
        } elseif ($item instanceof \MongoDB\BSON\MaxKey) {
            return '$MaxKey';
        } elseif ($item instanceof \MongoDB\BSON\MinKey) {
            return '$MinKey';
        } else {
            return \mongo\format\JSON::encode($item);
        }
    }
}
