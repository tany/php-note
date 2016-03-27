<?php

namespace mongo\feature;

\Klass::bind(__NAMESPACE__ . '\Timestamp', [
    'beforeValidate' => function() {
        $date = new \DateTime;
        $this->created = $this->created ?? $date;
        $this->updated = $date;
    },
]);

trait Timestamp {
    // $created
    // $updated
}
