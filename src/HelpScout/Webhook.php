<?php
namespace HelpScout;

require_once 'ClassLoader.php';

final class Webhook {
	private $input     = false;
	private $secretKey = false;

	public function __construct($secretKey) {
		\HelpScout\ClassLoader::register();
        require_once realpath(__DIR__ ."/../HelpScout/functions.php");

		$this->secretKey = $secretKey;
	}

	public function isTestEvent() {
		return $this->getEventType() === 'helpscout.test';
	}

	/**
	 * Is the current event a type of conversation event
	 * @return boolean
	 */
	public function isConversationEvent() {
		return $this->isEventTypeOf('convo');
	}

	/**
	 * Is the current event a type of customer event
	 * @return boolean
	 */
	public function isCustomerEvent() {
		return $this->isEventTypeOf('customer');
	}

	private function isEventTypeOf($eventType) {
		$header = $this->getEventType();
		if ($header) {
			if (substr($header, 0, strlen($eventType)) === $eventType) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the event type
	 * @return string
	 */
	public function getEventType() {
		return $this->findHeader(array('HTTP_X_HELPSCOUT_EVENT', 'X_HELPSCOUT_EVENT'));
	}

	private function getHeader($header) {
		if (isset($_SERVER[$header])) {
			return $_SERVER[$header];
		}
		$theVal = false;
		foreach($_SERVER as $headerVal => $headerText) {
			if (strtoupper($headerVal) == $header) {
				$theVal = $headerText;
				break;
			}
		}
		return $theVal;
	}

	private function findHeader($list) {
		$val = false;
		foreach($list as $header) {
			$val = $this->getHeader($header);
			if ($val) {
				break;
			}
		}
		return $val;
	}

	/**
	 * Returns true if the current request is a valid webhook issued from Help Scout, false otherwise.
	 * @return boolean
	 */
	public function isValid() {
		$signature = $this->generateSignature();
        $headers = $this->findHeader(['HTTP_X_HELPSCOUT_SIGNATURE', 'X_HELPSCOUT_SIGNATURE']);
		if ($signature && $headers !== false) {
			return hash_equals($signature, $headers);
		}
		return false;
	}

	private function generateSignature() {
		$str = $this->getJsonString();
		if ($str) {
			return base64_encode(hash_hmac('sha1', $str, $this->secretKey, true));
		}
		return false;
	}

	/**
	 * @return \HelpScout\model\Conversation
	 */
	public function getConversation() {
		$obj = $this->getObject();
		if ($obj) {
			return new \HelpScout\model\Conversation($obj);
		}
		return false;
	}


	/**
	 * @return \HelpScout\model\Customer
	 */
	public function getCustomer() {
		$obj = $this->getObject();
		if ($obj) {
			return new \HelpScout\model\Customer($obj->customer);
		}
		return false;
	}

	/**
	 * Returns FALSE if no input, or input cannot be decoded. Otherwise, returns an stdClass instance.
	 * @return \stdClass
	 */
	public function getObject() {
		$str = $this->getJsonString();
		if ($str) {
			return json_decode($str);
		}
		return false;
	}

	private function getJsonString() {
		if ($this->input === false) {
			$this->input = @file_get_contents('php://input');
		}
		return $this->input;
	}
}
