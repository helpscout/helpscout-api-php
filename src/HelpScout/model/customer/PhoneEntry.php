<?php
namespace HelpScout\model\customer;

class PhoneEntry extends CustomerEntry {
	
	/**
	 * @return string
	 */
	public function getType() {
		return parent::getLocation();
	}
}