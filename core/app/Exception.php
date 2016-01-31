<?php

namespace app;

class Exception {

    protected static $codes = [
        0                   => 'Exception',             // 0
        E_ERROR             => 'Error',                 // 1
        E_WARNING           => 'Warning',               // 2
        E_PARSE             => 'Parse Error',           // 4
        E_NOTICE            => 'Notice',                // 8
        E_CORE_ERROR        => 'Core Error',            // 16
        E_CORE_WARNING      => 'Core Warning',          // 32
        E_COMPILE_ERROR     => 'Compile Error',         // 64
        E_COMPILE_WARNING   => 'Compile Warning',       // 128
        E_USER_ERROR        => 'User Error',            // 256
        E_USER_WARNING      => 'User Warning',          // 512
        E_USER_NOTICE       => 'User Notice',           // 1024
        E_STRICT            => 'Strict Notice',         // 2048
        E_RECOVERABLE_ERROR => 'Recoverable Error',     // 4096
        E_DEPRECATED        => 'Deprecated Error',      // 8192
        E_USER_DEPRECATED   => 'User Deprecated Error', // 16384
        E_ALL               => 'All Error',             // 32767
    ];

    public static function register() {
        set_exception_handler('app\Exception::catch');
    }

    public static function catch($e) {
        if (ob_get_level()) $buf = ob_clean();

        $type  = self::$codes[$e->getCode()] ?? '';
        $title = "{$type}: {$e->getMessage()}";
        $file  = $e->getFile();
        $line  = $e->getLine();
        $trace = $e->getTrace();

        if (preg_match('/view\/Engine.* eval/', $file)) {
            $idx  = ($trace[0]['function'] === 'eval') ? 1 : 0;
            $file = $trace[$idx]['args'][2];
            $code = self::preview($trace[$idx]['args'][0], $line);
        } elseif (is_file($file)) {
            $code = self::preview(file_get_contents($file), $line);
        }

        $trace = self::trace($e);

        include 'exception/template.php';
        exit;
    }

    protected static function preview($text, $line) {
        $sline = ($line - 5) > 0 ? $line - 5 : 0;

        $list = array_kmap(function($key, $str) use ($line, $sline) {
            $no  = $sline + $key + 1;
            $str = htmlspecialchars($str, ENT_QUOTES);
            $str = str_replace("\t", '    ', $str);
            if ($no === $line) $str = "<span class='er'>{$str}</span>";
            return sprintf('<span class="no">%5s</span>%s', $no, $str);
        }, array_slice(preg_split('/(\r\n|\n|\r)/', $text), $sline, 9));

        return join("\n", $list);
    }

    protected static function trace($e) {
        $list = array_map(function($str) {
            $str = htmlspecialchars($str, ENT_QUOTES);
            list($no, $str) = preg_split('/ /', $str, 2);
            return sprintf('<span class="no">%5s</span>%s', $no, $str);
        }, preg_split('/\n/', $e->getTraceAsString()));

        return join("\n", $list);
    }
}
