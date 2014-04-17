<?php
namespace HelpScout\model\thread;

class Customer extends AbstractThread {

	public function __construct($data=null) {
		parent::__construct($data);
		$this->setType('customer');
	}

	/**
	 * @param \HelpScout\model\ref\PersonRef $createdBy
	 */
	public function setCreatedBy(\HelpScout\model\ref\PersonRef $createdBy) {
		if ($createdBy) {
			if ($createdBy->getType() !== 'customer') {
				throw new \HelpScout\ApiException('A customer thread can only be created by a PersonRef of type customer.');
			}
		}
		parent::setCreatedBy($createdBy);
	}
}
