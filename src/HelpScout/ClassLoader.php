<?php
namespace HelpScout;

final class ClassLoader {
	const NAMESPACE_SEPARATOR = '\\';

	private $baseDir = false;

	/**
	 * @var \HelpScout\ClassLoader
	 */
	private static $instance = false;

	private function __construct() {
		spl_autoload_register(array($this,'autoload'));
		$this->baseDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	}

	public function __destruct() {
		spl_autoload_unregister(array($this, 'autoload'));
	}

	public function autoload($className) {
		if (strpos($className, 'HelpScout') === false) {
			return false;
		}
		$className = str_replace(
				array(
						self::NAMESPACE_SEPARATOR . 'HelpScout' . self::NAMESPACE_SEPARATOR,
						'HelpScout' . self::NAMESPACE_SEPARATOR
				), '', $className
		);
		require_once ($this->baseDir . str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $className) . '.php');
		return true;
	}

	public static function register() {
		if (self::$instance === false) {
			self::$instance = new ClassLoader();
		}
	}
}
