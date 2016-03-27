<?php

namespace sys\controller\db;

class Documents extends \app\Controller {

    use \sys\filter\Base;
    use \sys\filter\Crud;
    use \sys\filter\Db;

    protected function model() {
        $ns = "{$this->request->db}.{$this->request->coll}";
        return \sys\model\Document::scope()->setNs($ns);
    }

    public function index($request) {
        $this->items = $this->model()
            ->sort(['$natural' => -1])
            ->page($request->page, 20);

        $this->fields = $this->fields($this->items);
    }

    protected function fields($items) {
        $fields = [];
        foreach ($items as $item) {
            foreach ($item->data() as $key => $val) {
                if ($val) $fields[$key] = 1;
            }
        }
        ksort($fields);
        return array_keys($fields);
    }

    public function add($request) {
        $item = $this->model();
        $this->item = $item;

        if (!$request->isPost()) return;
        if (isset($request->{'save-yaml'})) $item->parseYaml($request->data['yaml']);
        if (isset($request->{'save-json'})) $item->parseJson($request->data['json']);
        if ($item->save()) return $this->redirect("?");
    }

    public function edit($request) {
        $item = $this->model()->find($request->id);
        $this->item = $item;

        if (!$request->isPost()) return;
        if (isset($request->{'save-yaml'})) $item->parseYaml($request->data['yaml']);
        if (isset($request->{'save-json'})) $item->parseJson($request->data['json']);
        if ($item->save()) return $this->redirect("?");
    }
}
