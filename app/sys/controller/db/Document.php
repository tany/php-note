<?php

namespace sys\controller\db;

class Document extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Crud;
    use \sys\filter\Db;

    protected function model() {
        $ns = "{$this->request->db}.{$this->request->coll}";
        return \sys\AnyModel::scope()->setNS($ns);
    }

    public function index($request) {
        $this->items = $this->model()
            ->sort(['_id' => -1]);

        $fields = [];
        foreach ($this->items as $item) {
            foreach ($item->data() as $key => $val) $fields[$key] = 1;
        }
        ksort($fields);
        $this->fields = array_keys($fields);
    }

    public function see($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;
    }

    public function create($request) {
        $item = $this->model();
        $this->item = $item;

        if (!$request->isPost()) return;
        if (isset($request->{'save-yaml'})) $item->parseYaml($request->data['yaml']);
        if (isset($request->{'save-json'})) $item->parseJson($request->data['json']);
        if ($item->save()) return $this->redirect("?");
    }

    public function update($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        if (isset($request->{'save-yaml'})) $item->parseYaml($request->data['yaml']);
        if (isset($request->{'save-json'})) $item->parseJson($request->data['json']);
        if ($item->save()) return $this->redirect("?");
    }

    public function delete($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        if ($item->remove()) return $this->redirect("./");
    }

    public function deleteAll($request) {
        foreach ($request->id as $id) {
            $item = $this->model()->find($id);
            $item->remove();
        }
        return $this->redirect("./");
    }
}
