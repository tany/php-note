<?php

namespace app;

class Response {

    protected $head = [];
    protected $code;
    protected $type;
    protected $body;

    public function header(...$params) {
        $this->head[] = $params;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function code() {
        return $this->code;
    }

    public function type() {
        return $this->type;
    }

    public function body() {
        return $this->body;
    }

    public function capture() {
        $this->body = ob_get_contents();
        ob_clean();
        return $this;
    }

    public function cache($mtime) {
        // opcached time
        //$stats = opcache_get_status();
        //$times = array_map(function($stat) { return $stat['timestamp']; }, $stats['scripts']);
        //$mtime = max(max($times), $mtime);

        $since = 'HTTP_IF_MODIFIED_SINCE';
        if (isset($_SERVER[$since]) && $mtime === strtotime($_SERVER[$since])) {
            $this->setCode(304);
            return true;
        }
        if ($mtime) {
            //header('Pragma: cache');
            //header('Cache-Control: max-age=3600, public');
            //header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600));
            $this->header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
        }
        return false;
    }

    public function send() {
        if ($this->code) header('HTTP', true, $this->code);
        if ($this->type) header("Content-Type: {$this->type}");

        foreach ($this->head as $head) {
            call_user_func_array('header', $head);
        }
        print $this->body;
        return $this;
    }
}
