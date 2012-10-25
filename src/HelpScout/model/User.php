<?php
namespace HelpScout\model;

class User {
	private $id = false;
	private $firstName;
	private $lastName;
	private $email;
	private $role;
	private $timezone;
	private $photoUrl;
	private $createdAt;
	private $modifiedAt;

	public function __construct($data=null) {
		if ($data) {
			$this->id         = $data->id;
			$this->firstName  = $data->firstName;
			$this->lastName   = $data->lastName;
			$this->email      = $data->email;
			$this->role       = $data->role;
			$this->timezone   = $data->timezone;
			$this->photoUrl   = $data->photoUrl;
			$this->createdAt  = $data->createdAt;
			$this->modifiedAt = $data->modifiedAt;
		}
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @return string
	 */
	public function getFullName() {
		return trim(sprintf('%s %s', $this->firstName, $this->lastName));
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return boolean
	 */
	public function isOwnerRole() {
		return $this->role == 'owner';
	}

	/**
	 * @return boolean
	 */
	public function isAdminRole() {
		return $this->role == 'admin';
	}

	/**
	 * @return boolean
	 */
	public function isUserRole() {
		return $this->role == 'user';
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @return string
	 */
	public function getTimezone() {
		return $this->timezone;
	}

	/**
	 * @return boolean
	 */
	public function hasPhoto() {
		return !$this->isEmpty($this->photoUrl);
	}

	/**
	 * @return string
	 */
	public function getPhotoUrl() {
		return $this->photoUrl;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @return string
	 */
	public function getModifiedAt() {
		return $this->modifiedAt;
	}

	private function isEmpty($value) {
		$v = trim($value);
		return empty($v);
	}

	/**
	 * @return \HelpScout\model\ref\UserRef
	 */
	public function toRef() {
		$ref = new \HelpScout\model\ref\UserRef();
		$ref->setId       ($this->id);
		$ref->setFirstName($this->firstName);
		$ref->setLastName ($this->lastName);

		return $ref;
	}
}
