<?php

namespace app\form;

class Form {

    public static $item;

    public static function open($item) {
        Form::$item = $item;
        return '<form method="post" action="">';
    }

    public static function close() {
        Form::$item = null;
        return '</form>';
    }

    public static function input($name) {
        return element\Input::render($name);
    }
}
