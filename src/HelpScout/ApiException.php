<?php
namespace HelpScout;

class ApiException extends \Exception {

	protected $errors = array();

	public function getErrors()
	{
		return $this->errors;
	}

	public function setErrors(array $errors = array())
	{
		$this->errors = $errors;
		return $this;
	}

}
