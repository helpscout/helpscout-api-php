<?php
namespace HelpScout\model\ref;

class UserRef extends PersonRef {
	public function __construct($data=null) {
		parent::__construct($data);
		$this->setType('user');
	}
}
