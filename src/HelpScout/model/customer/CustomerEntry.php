<?php
namespace HelpScout\model\customer;

class CustomerEntry extends Object {

	private $value;

	public function __toString() {
    	return 'Customer Entry ID: ' . $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

}