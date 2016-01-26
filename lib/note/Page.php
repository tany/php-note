<?php

namespace note;

class Page {

    public function all() {
        $items = [];
        for ($i = 0; $i < 10; $i++) $items[] = $this->find($i + 1);
        return $items;
    }

    public function find($id) {
        $item = new \stdClass;
        $item->id = $id;
        $item->uid = 'user01';
        $item->title = 'あいうえお！？かきくけこさしすせそ';
        $item->body = str_repeat("たちつてと、なにぬねのはひふへほまみむめも。", 30);
        $item->updated = date('Y/n/j H:i');
        return $item;
    }
}
