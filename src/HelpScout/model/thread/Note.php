<?php
namespace HelpScout\model\thread;

class Note extends AbstractThread {

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
}
