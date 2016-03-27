<?php

namespace sys\controller\db;

class Collections extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Db;

    protected function model() {
        return \mongo\Connection::connect();
    }

    public function index($request) {
        $this->items = $this->model()->collections($request->db);
    }

    public function add($request) {
        if (!$request->isPost()) return;

        $name = $request->data['name'];
        $this->model()->command($request->db, ['create' => $name]);
        return $this->redirect("{$name}/");
    }

    public function dropAll($request) {
        foreach ($request->id as $id) {
            $this->model()->dropCollection($request->db, $id);
        }
        return $this->redirect("./");
    }

    public function rename($request) {
       if (!$request->isPost()) return;

        $fr = "{$request->db}.{$request->id}";
        $to = "{$request->db}.{$request->data['name']}";
        $this->model()->adminCommand(['renameCollection' => $fr, 'to' => $to]);
        return $this->redirect("{$request->data['name']}/");
    }
}
