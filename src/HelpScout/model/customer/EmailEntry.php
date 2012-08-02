<?php
namespace HelpScout\model\customer;

class EmailEntry extends CustomerEntry {
	const TYPE_HOME   = 1;
	const TYPE_WORK   = 2;
	const TYPE_OTHER  = 3;

	public function getType() {
		return 'email';
	}
	
	public function getLabel() {
		switch($this->locationType) {
			case self::TYPE_WORK:
				return 'Work';
			case self::TYPE_HOME:
				return 'Home';
			default:
				return 'Other';
		}
	}

	/**
	 *
	 * @param string $identifier
	 * @return int
	 */
	public static function getTypeByIdentifier($identifier) {
		$identifier = strtolower(trim($identifier));
		switch($identifier) {
			case 'work':
				return self::TYPE_WORK;
			case 'home':
				return self::TYPE_HOME;
			default:
				return self::TYPE_OTHER;
		}
	}
}