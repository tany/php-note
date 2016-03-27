<?php

namespace mongo\type;

use \MongoDB\BSON\Serializable;

class Timestamp extends \DateTime implements Serializable {

    public function __toString() {
        $tz = gmdate('H:i', $this->getOffset());
        return date('Y-m-d\TH:i:s+' . $tz, $this->getTimestamp());
    }

    public static function fromString($str) {
        return new self($str);
    }

    public static function fromBSON($bson) {
        $val = explode(':', trim($bson->__toString(), '[]'));
        return new self(date('Y-m-d\TH:i:s+' . gmdate('H:i', $val[1]), $val[0]));
    }

    public function bsonSerialize() {
        return new \MongoDB\BSON\Timestamp($this->getTimestamp(), $this->getOffset());
    }
}
