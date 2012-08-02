<?php
namespace HelpScout\model\customer;

class PhoneEntry extends CustomerEntry {
	const TYPE_WORK   = 1;
	const TYPE_HOME   = 2;
	const TYPE_MOBILE = 3;
	const TYPE_FAX    = 4;
	const TYPE_PAGER  = 5;
	const TYPE_OTHER  = 6;

	public function getType() {
		return 'phone';
	}
	public function getLabel() {
		switch($this->locationType) {
			case self::TYPE_WORK:
				return 'Work';
			case self::TYPE_HOME:
				return 'Home';
			case self::TYPE_MOBILE:
				return 'Mobile';
			case self::TYPE_FAX:
				return 'Fax';
			case self::TYPE_PAGER:
				return 'Pager';
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
		switch($identifier) {
			case 'work':
				return self::TYPE_WORK;
			case 'home':
				return self::TYPE_HOME;
			case 'mobile':
			case 'cell':
				return self::TYPE_MOBILE;
			case 'fax':
				return self::TYPE_FAX;
			case 'pager':
				return self::TYPE_PAGER;
			default:
				return self::TYPE_OTHER;
		}
	}

	public static function getOptionsList() {
		return array(
			self::TYPE_FAX    => 'Fax',
			self::TYPE_HOME   => 'Home',
			self::TYPE_MOBILE => 'Mobile',
			self::TYPE_PAGER  => 'Pager',
			self::TYPE_WORK   => 'Work',
			self::TYPE_OTHER  => 'Other'
		);
	}
}