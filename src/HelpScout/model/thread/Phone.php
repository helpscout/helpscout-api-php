<?php
namespace HelpScout\model\thread;

class Phone extends AbstractThread
{
    public function __construct($data=null)
    {
        parent::__construct($data);
        $this->setType('phone');
    }
}
