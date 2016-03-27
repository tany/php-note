<?php

namespace app;

class Logger {

    protected static $file = ROOT . '/log/trace.log';

    protected static $logs = [];

    public static function register() {
        register_shutdown_function('app\Logger::flush');
    }

    public static function dump($data) {
        self::$logs[] = self::format($data);
    }

    public static function trace($data) {
        file_put_contents(self::$file, "\n" . self::format($data), FILE_APPEND | LOCK_EX);
    }

    protected static function format($data) {
        if ($data === null) return var_export($data, true);
        if (is_bool($data)) return var_export($data, true);
        if (is_string($data)) return str_replace('$', '\$', $data);
        if (is_numeric($data)) return $data;

        $data = print_r($data, true);
        return preg_replace('/\n\s*\(/', ' (', $data);
    }

    public static function flush() {
        try {
            if ($e = error_get_last()) self::dump($e);

            if (($type = self::contentType()) === 'text/html') {
                $buf = ob_get_level() ? ob_get_clean() : null;
                print preg_replace('/<\/body>/', self::html() . '</body>', $buf, 1, $count);
                if (!$count) print self::html();
            } elseif ($type === 'text/plain') {
                print self::text();
            }
        } catch (\Error $e) {
            if (ob_get_level()) ob_clean();
            throw $e;
        }
    }

    protected static function contentType() {
        foreach (headers_list() as $line) {
            if (preg_match('/^Content-Type: ?([\w\/]+)/i', $line, $m)) return $m[1];
        }
        return 'text/html';
    }

    protected static function time() {
        return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    }

    protected static function html() {
        $buf = [];
        foreach (self::$logs as $log) {
            $buf[] = '<pre class="app-dump">' . h($log) . "</pre>\n";
        }
        $buf[] = '<div id="app-stat">';
        $buf[] = sprintf('<span class="cpu">%.2f ms</span>', self::time() * 1000);
        $buf[] = sprintf(' / <span class="mem">%d kb</span>', memory_get_peak_usage() / 1024);
        $buf[] = '</div>';
        return join('', $buf);
    }

    protected static function text() {
        $buf = [];
        foreach (self::$logs as $log) {
            $buf[] = "--\n{$log}\n";
        };
        $buf[] = sprintf("\n// %.2f ms", self::time() * 1000);
        $buf[] = sprintf("\n// %d kb", memory_get_usage() / 1024);
        return join('', $buf);
    }
}
