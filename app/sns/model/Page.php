<?php

namespace sns\model;

class Page extends \mongo\Model {

    //use \mongo\feature\SequenceId;
    use \mongo\feature\Timestamp;

    public $curUser;

    public function collectionName() {
        return 'sns_page';
    }
}
