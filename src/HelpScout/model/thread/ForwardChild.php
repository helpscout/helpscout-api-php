<?php
namespace HelpScout\model\thread;

class ForwardChild extends AbstractThread {

	public function __construct($data=null) {
		parent::__construct($data);
		$this->setType('forwardchild');
	}
}
