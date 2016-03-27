<?php

namespace sys\controller\db;

class Databases extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Db;

    protected function model() {
        return \mongo\Connection::connect();
    }

    public function index($request) {
        $this->items = $this->model()->databases();
    }

    public function add($request) {
        if (!$request->isPost()) return;
        return $this->redirect("{$request->data['name']}/");
    }

    public function dropAll($request) {
        foreach ($request->id as $id) {
            $this->model()->dropDatabase($id);
        }
        return $this->redirect("./");
    }

    public function command($request) {
        if (!$request->isPost()) return;

        $this->item = (object)['json' => null];
        $resp = $this->model()->command($request->data['db'], \mongo\format\Json::decode($request->data['json']));

        dump($resp);
        //return $this->redirect("?");
    }
}
