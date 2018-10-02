<?php
namespace HelpScout\model\thread;

class BeaconChat extends AbstractThread {
    public function __construct($data=null) {
        parent::__construct($data);
        $this->setType('beaconchat');
    }
}
