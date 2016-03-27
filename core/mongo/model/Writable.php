<?php

namespace mongo\model;

trait Writable {

    public function save() {
        $this->valid();

        foreach ($this->classBinds('beforeSave') as $func) $func->call($this);
        $this->beforeSave();

        $data = $this->toBSON();

        if ($this->isNewDocument()) {
            $result = $this->_id = $this->insert($data);
        } else {
            $result = $this->update($data);
        }

        foreach ($this->classBinds('afterSave') as $func) $func->call($this);
        $this->afterSave();

        return $result;
    }

    protected function beforeSave() {
        //
    }

    protected function afterSave() {
        //
    }

    public function insert($data) {
        return $this->connect()->insert($this->ns(), $data);
    }

    public function update($update) {
        return $this->connect()->update($this->ns(), ['_id' => $this->_saved['_id']], $update);
    }

    public function updateAll($query = [], $update = [], $options = []) {
        return $this->connect()->updateAll($this->ns(), $query, $update, $options);
    }

    public function remove() {
        return $this->connect()->remove($this->ns(), ['_id' => $this->_saved['_id']]);
    }

    public function removeAll($query = [], $options = []) {
        return $this->connect()->removeAll($this->ns(), $query, $options);
    }
}
