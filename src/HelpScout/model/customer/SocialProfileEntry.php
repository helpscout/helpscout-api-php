<?php
namespace HelpScout\model\customer;

class SocialProfileEntry extends CustomerEntry {
	const TYPE_TWITTER     = 1;
	const TYPE_FACEBOOK    = 2;
	const TYPE_LINKEDIN    = 3;
	const TYPE_ABOUTME     = 4;
	const TYPE_GOOGLE      = 5;
	const TYPE_GOOGLE_PLUS = 6;
	const TYPE_TUNGLEME    = 7;
	const TYPE_QUORA       = 8;
	const TYPE_FOURSQUARE  = 9;
	const TYPE_YOUTUBE     = 10;
	const TYPE_FLICKR      = 11;
	const TYPE_OTHER       = 12;

	private static $TYPES = array(
		'twitter'       => self::TYPE_TWITTER,
		'facebook'      => self::TYPE_FACEBOOK,
		'linkedin'      => self::TYPE_LINKEDIN,
		'aboutme'       => self::TYPE_ABOUTME,
		'googleprofile' => self::TYPE_GOOGLE,
		'googleplus'    => self::TYPE_GOOGLE_PLUS,
		'tungleme'      => self::TYPE_TUNGLEME,
		'quora'         => self::TYPE_QUORA,
		'foursquare'    => self::TYPE_FOURSQUARE,
		'youtube'       => self::TYPE_YOUTUBE,
		'flickr'        => self::TYPE_FLICKR
	);

	public function getLabel() {
		if ($this->systemType == self::TYPE_TWITTER) {
			$parts = explode('/', $this->getValue());
			return '@' . end($parts);
		}
		list(, $title) = $this->getClassAndTitleForValueType();
		return $title;
	}

	public function toArray() {
		$vars = parent::toArray();

		list($clazz, $title) = $this->getClassAndTitleForValueType();

		$vars['class'] = $clazz;
		$vars['title'] = $title;

		if (\WebUtils::isHttps()) {
			$vars['value'] = str_replace('http://', 'https://', $vars['value']);
		}
		return $vars;
	}

	private function getClassAndTitleForValueType() {
		switch($this->systemType) {
			case self::TYPE_TWITTER:
				return array('tw', 'Twitter');

			case self::TYPE_FACEBOOK:
				return array('fb', 'Facebook');

			case self::TYPE_LINKEDIN:
				return array('li', 'LinkedIn');

			case self::TYPE_ABOUTME:
				return array('ab', 'About.me');

			case self::TYPE_GOOGLE:
				return array('go', 'Google');

			case self::TYPE_GOOGLE_PLUS:
				return array('go', 'Google+');

			case self::TYPE_TUNGLEME:
				return array('tu', 'Tungle.me');

			case self::TYPE_QUORA:
				return array('qu', 'Quora');

			case self::TYPE_FOURSQUARE:
				return array('fs', 'Foursquare');

			case self::TYPE_YOUTUBE:
				return array('yt', 'YouTube');

			case self::TYPE_FLICKR:
				return array('fl', 'Flickr');
		}
		return array('', 'Other');
	}

	/**
	 * @param string $type
	 * @return int
	 */
	public static function getTypeByIdentifier($type) {
		if (array_key_exists($type, self::$TYPES)) {
			return self::$TYPES[$type];
		}
		return false;
	}

	public static function getOptionsList() {
		return array(
			self::TYPE_ABOUTME    => 'About.me',
			self::TYPE_FACEBOOK   => 'Facebook',
			self::TYPE_FLICKR     => 'Flickr',
			self::TYPE_FOURSQUARE => 'Foursquare',
			self::TYPE_GOOGLE     => 'Google',
			self::TYPE_GOOGLE_PLUS=> 'Google+',
			self::TYPE_LINKEDIN   => 'Linkedin',
			self::TYPE_QUORA      => 'Quora',
			self::TYPE_TUNGLEME   => 'Tungle.me',
			self::TYPE_TWITTER    => 'Twitter',
			self::TYPE_YOUTUBE    => 'YouTube',
			self::TYPE_OTHER      => 'Other'
		);
	}
}