<?php

namespace app;

class Exception {

    public static function register() {
        set_exception_handler('app\Exception::catch');
    }

    public static function catch($e) {
        if (ob_get_level()) $buf = ob_clean();

        echo '<body><pre>';
        echo $e->__toString();
        echo '</pre></body>';
        exit;
    }
}
