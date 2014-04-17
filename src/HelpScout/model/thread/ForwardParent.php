<?php
namespace HelpScout\model\thread;

class ForwardParent extends AbstractThread {
	public function __construct($data=null) {
		parent::__construct($data);
		$this->setType('forwardparent');
	}
}
