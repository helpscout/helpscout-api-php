<?php
namespace HelpScout\model\ref;

class CustomerRef extends PersonRef {
	public function __construct($data=null) {
		parent::__construct($data);
		$this->setType('customer');
	}
}
