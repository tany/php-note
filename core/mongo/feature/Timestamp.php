<?php

namespace mongo\feature;

\Klass::bind(Timestamp::class, 'beforeValidate', function() {
    $date = new \DateTime;
    $this->created = $this->created ?? $date;
    $this->updated = $date;
});

trait Timestamp {
    // $created
    // $updated
}
