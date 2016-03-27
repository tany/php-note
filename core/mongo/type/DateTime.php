<?php

namespace mongo\type;

use \MongoDB\BSON\Serializable;

class DateTime extends \DateTime implements Serializable {

    public function __toString() {
        return $this->format(DATE_ATOM);
    }

    public static function fromString($str) {
        return new self($str);
    }

    public static function fromBSON($bson) {
        $tz = new \DateTimeZone(date_default_timezone_get());
        return (new self('@' . substr($bson->__toString(), 0, -3)))->setTimeZone($tz);
    }

    public function bsonSerialize() {
        return new \MongoDB\BSON\UTCDateTime($this->getTimestamp() * '1000');
    }
}
