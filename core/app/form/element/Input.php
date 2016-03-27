<?php

namespace app\form\element;

class Input {

    public static function attrString($data) {
        $h = [];
        foreach ($data as $key => $val) $h[] = $key . '="' . h($val) . '"';
        return join(' ', $h);
    }

    public static function make($name, $attr) {
        $label = camelize($name);

        return '<div class="mdl-textfield mdl-js-textfield sns-field">' .
            '<label class="mdl-textfield__label">' . $label . '</label>' .
            '<input class="mdl-textfield__input"' . self::attrString($attr) . '/>' .
            '</div>';
    }

    public static function render($name) {
        $field = "data[{$name}]";
        $value = \app\form\Form::$item->$name ?? '';

        $attr = [
            'type' => 'text',
            'name' => $field,
            'value' => $value,
            'required' => 'required',
        ];
        return self::make($name, $attr);
    }
}
