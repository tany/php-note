<?php

namespace mongo\model;

trait Format {

    public function parseJson($data) {
        $this->_data = \mongo\format\Json::decode($data);
        return $this;
    }

    public function toJson() {
        return \mongo\format\Json::encode($this->_data);
    }

    public function parseYaml($data) {
        $this->_data = \mongo\format\Yaml::decode($data);
        return $this;
    }

    public function toYaml() {
        return \mongo\format\Yaml::encode($this->_data);
    }
}
