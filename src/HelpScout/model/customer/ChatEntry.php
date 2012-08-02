<?php
namespace HelpScout\model\customer;

class ChatEntry extends Object {
	const TYPE_AIM   = 1;
	const TYPE_GTALK = 2;
	const TYPE_ICQ   = 3;
	const TYPE_XMPP  = 4;
	const TYPE_MSN   = 5;
	const TYPE_SKYPE = 6;
	const TYPE_YAHOO = 7;
	const TYPE_QQ    = 8;
	const TYPE_OTHER = 9;

	public function getType() {
		return 'chat';
	}

	public function getLabel() {
		switch($this->systemType) {
			case self::TYPE_AIM:
				return 'AIM';
			case self::TYPE_GTALK:
				return 'GTALK';
			case self::TYPE_ICQ:
				return 'ICQ';
			case self::TYPE_XMPP:
				return 'XMPP';
			case self::TYPE_MSN:
				return 'MSN';
			case self::TYPE_SKYPE:
				return 'Skype';
			case self::TYPE_YAHOO:
				return 'Yahoo';
			case self::TYPE_QQ:
				return 'QQ';
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
			case 'aim':
				return self::TYPE_AIM;
			case 'gtalk':
				return self::TYPE_GTALK;
			case 'icq':
				return self::TYPE_ICQ;
			case 'xmpp':
				return self::TYPE_XMPP;
			case 'msn':
				return self::TYPE_MSN;
			case 'skype':
				return self::TYPE_SKYPE;
			case 'yahoo':
				return self::TYPE_YAHOO;
			case 'qq':
				return self::TYPE_QQ;
			default:
				return self::TYPE_OTHER;
		}
	}

	public static function getOptionsList() {
		return array(
			self::TYPE_AIM    => 'AIM',
			self::TYPE_GTALK  => 'GTALK',
			self::TYPE_ICQ    => 'ICQ',
			self::TYPE_MSN    => 'MSN',
			self::TYPE_QQ     => 'QQ',
			self::TYPE_SKYPE  => 'Skype',
			self::TYPE_XMPP   => 'XMPP',
			self::TYPE_YAHOO  => 'Yahoo',
			self::TYPE_OTHER  => 'Other'
		);
	}
}
