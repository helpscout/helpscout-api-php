<?php
class DynamicApp {
	const SECRET_KEY = 'SECRET-KEY-HERE';

	private $input = false;

	private function getHeader($header) {
		if (isset($_SERVER[$header])) {
			return $_SERVER[$header];
		}
		return false;
	}

	private function getJsonString() {
		if ($this->input === false) {
			$this->input = @file_get_contents('php://input');
		}
		return $this->input;
	}

	private function generateSignature() {
		$str = $this->getJsonString();
		if ($str) {
			return base64_encode(hash_hmac('sha1', $str, self::SECRET_KEY, true));
		}
		return false;
	}

	/**
	 * Returns true if the current request is a valid webhook issued from Help Scout, false otherwise.
	 * @return boolean
	 */
	private function isSignatureValid() {
		$signature = $this->generateSignature();
		$header    = $this->getHeader('HTTP_X_HELPSCOUT_SIGNATURE');
		if ($signature && $header !== false) {
            return hash_equals($signature, $header);
		}
		return false;
	}

	private function getHelpScoutData() {
		$this->getJsonString(); //ensure data has been loaded from input
		return json_decode($this->input, true);
	}

	public function getResponse() {
		$ret = array('html' => '');

		if (!$this->isSignatureValid()) {
			return;
		}
		$data = $this->getHelpScoutData();

		$ticket   = $data['ticket'];
		$customer = $data['customer'];

		// do some stuff
		$ret['html'] = $this->fetchHtml();

		return json_encode($ret);
	}

	private function fetchHtml() {
		return '<ul><li>hello there</li></ul>';
	}
}

$app = new DynamicApp();
echo $app->getResponse();
