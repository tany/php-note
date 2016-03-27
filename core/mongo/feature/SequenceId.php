<?php

namespace mongo\feature;

\Klass::bind(__NAMESPACE__ . '\SequenceId', [
    'schema' => function() {
        return ['_id' => ['type' => 'integer']];
    },
    'beforeSave' => function() {
        if ($this->_id === null) $this->_id = $this->nextId();
    },
]);

trait SequenceId {

    public function nextId() {
        if (isset($this->_id)) return;

        $command = [
            'findAndModify' => 'sequence',
            'query'         => ['_id' => "{$this->collectionName()}._id"],
            'update'        => ['$inc' => ['seq' => 1]],
            "upsert"        => true,
            "new"           => true,
        ];
        $cursor = $this->connect()->command($this->dbName(), $command);
        return current($cursor->toArray())->value->seq;
    }
}
