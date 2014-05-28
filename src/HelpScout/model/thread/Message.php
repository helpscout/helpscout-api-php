<?php
namespace HelpScout\model\thread;

class Message extends AbstractThread {
	public function __construct($data=null) {
		parent::__construct($data);
		$this->setType('message');
	}

	/**
	 * @param \HelpScout\model\ref\PersonRef $createdBy
	 */
	public function setCreatedBy(\HelpScout\model\ref\PersonRef $createdBy) {
		if ($createdBy) {
			if ($createdBy->getType() !== 'user') {
				throw new \HelpScout\ApiException('A note thread can only be created by a PersonRef of type user');
			}
		}
		parent::setCreatedBy($createdBy);
	}

	public function getOpenedAt() {
		return $this->openedAt;
	}
}
