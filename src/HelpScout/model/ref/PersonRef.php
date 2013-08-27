<?php
namespace HelpScout\model\ref;

class PersonRef {
	private $id;
	private $firstName;
	private $lastName;
	private $email;
	private $phone;
	private $type;

	public function __construct($data=null) {
		if ($data) {
			$this->id         = isset($data->id)        ? $data->id        : null;
			$this->firstName  = isset($data->firstName) ? $data->firstName : null;
			$this->lastName   = isset($data->lastName)  ? $data->lastName  : null;
			$this->email      = isset($data->email)     ? $data->email     : null;
			$this->phone      = isset($data->phone)     ? $data->phone     : null;
			$this->type       = isset($data->type)      ? $data->type      : null;
		}
	}

	public function getObjectVars() {
		$vars = get_object_vars($this);
		if (isset($vars['id']) && $vars['id'] == false){
			unset($vars['id']);
		}
		return $vars;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	public function setType($type) {
		$type = strtolower(trim($type));
		if (!in_array($type, array('customer','user'))) {
			throw new \Exception('Invalid type set on PersonRef [' . $type . ']. Must be one of type customer or user.');
		}
		$this->type = $type;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function getId() {
		return $this->id;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function getType() {
		return $this->type;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getPhone() {
		return $this->phone;
	}
}
