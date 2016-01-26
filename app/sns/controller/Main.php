<?php

namespace sns\controller;

class Main extends \app\Controller {

    use \sns\filter\Base;

    public function index() {
        //$this->find();

        $this->items = (new \note\Page)->all();
    }

    public function find() {
        $manager = new \MongoDB\Driver\Manager();

        $filter = [];
        $options = [];
        $query = new \MongoDB\Driver\Query($filter, $options);

        $cursor = $manager->executeQuery('ss.cms_pages', $query);
        foreach ($cursor as $document) {
            dump($document->name);
        }
    }
}
