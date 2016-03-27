<?php

namespace sns\model;

class User extends \mongo\Model {

    //use \mongo\feature\SequenceId;
    use \mongo\feature\Timestamp;

    protected static function schema() {
        return [
            //'_id'   => ['type' => 'mongo\type\Sequence'],
            //'_id'   => ['type' => new mongo\type\Sequence],
            'uid' => ['type' => 'String'],
            'name' => ['type' => 'String'],
            'email' => ['type' => 'String', 'default' => 1],
        ];
    }

    protected function beforeValidate() {
        if ($this->inPassword) {
            $this->password = \app\util\Password::hash($this->inPassword);
            $this->inPassword = null;
        }
    }

    public function authenticate($uid, $password) {
        $item = (new self)
            ->where(['uid' => $uid])
            ->one();

        if ($item && \app\util\Password::verify($password, $item->password)) return $item;
        return false;
    }

    public function sessionSerialize() {
        return $this->_id; #TODO: encrypt
    }

    public function sessionUnserialize($data) {
        return (new self)->find((string)$data);
    }
}
