<?php

namespace sys\controller\db;

class Database extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Db;

    protected function model() {
        return \mongo\DB::connect();
    }

    public function index($request) {
        $this->items = $this->model()->databases();
    }

    public function create($request) {
        if (!$request->isPost()) return;
        return $this->redirect("{$request->data['name']}/");
    }

    public function deleteAll($request) {
        foreach ($request->id as $id) {
            $this->model()->dropDatabase($id);
        }
        return $this->redirect("./");
    }
}
