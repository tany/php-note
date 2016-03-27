<?php

namespace mongo\model;

trait Validatable {

    protected function beforeValidate() {
        //
    }

    protected function validate() {
        //
    }

    protected function afterValidate() {
        //
    }

    public function valid() {
        foreach ($this->classBinds('beforeValidate') as $func) $func->call($this);
        $this->beforeValidate();

        foreach ($this->classBinds('validate') as $func) $func->call($this);
        $this->validate();

        foreach ($this->classBinds('afterValidate') as $func) $func->call($this);
        $this->afterValidate();
    }
}
