<?php

namespace app\html;

class Pagination {

    protected $name = 'page';
    protected $wide = 4;
    protected $size;
    protected $page;
    protected $prev;
    protected $next;
    protected $last;
    protected $limit = 50;

    public function __toString() {
        return $this->render();
    }

    public function array($aray) {
        $this->size = count($array);
        return $this;
    }

    public function size($size) {
        $this->size = $size;
        return $this;
    }

    public function page($page) {
        $this->page = (int)$page;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function render() {
        $get  = $_GET;
        $name = $this->name;
        $wide = $this->wide;
        $page = $this->page;
        $last = ceil($this->size / $this->limit);
        $prev = ($page > 1) ? $page - 1 : null;
        $next = ($page < $last) ? $page + 1 : null;
        $snum = ($page - $wide < 1) ? 1 : $page - $wide;
        $enum = ($page + $wide > $last) ? $last : $page + $wide;

        $link = function($page) use ($get, $name) {
            return http_build_query([$name => $page] + $get);
        };

        $h = [];

        if ($prev)            $h[] = "<a class=\"pagination__item first\" href=\"?{$link(1)}\">«</a>";
        if ($prev)            $h[] = "<a class=\"pagination__item prev\" href=\"?{$link($prev)}\">Prev</a>";
        if ($snum > 1)        $h[] = '<span class="pagination__item gap">...</span>';
        if ($page <= $last && $last > 1) for ($i = $snum; $i <= $enum; $i++) {
            if ($i === $page) $h[] = "<span class=\"pagination__item page current\">{$i}</span>";
            else              $h[] = "<a class=\"pagination__item page\" href=\"?{$link($i)}\">{$i}</a>";
        }
        if ($enum < $last)    $h[] = '<span class="pagination__item gap">...</span>';
        if ($next)            $h[] = "<a class=\"pagination__item next\" href=\"?{$link($next)}\">Next</a>";
        if ($page < $last)    $h[] = "<a class=\"pagination__item last\" href=\"?{$link($last)}\">»</a>";
        if ($page > $last)    $h[] = "<span class=\"pagination__item page current\">{$page}</span>";

        return '<nav class="pagination">' . join('', $h) . '</nav>';
    }
}
