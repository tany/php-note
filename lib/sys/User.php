<?php

namespace sys;

class User extends \mongo\Model {

    protected static $_fields = [];

    public function __construct() {
        if (!self::$_fields) {
            $this->setFields();
        }
    }

    protected static function setFields() {
    }

    public static function collectionName() {
        return 'sys_user';
    }
}
