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
			$this->id         = isset($data->id        ) ? $data->id         : null;
			$this->firstName  = isset($data->firstName ) ? $data->firstName  : null;
			$this->lastName   = isset($data->lastName  ) ? $data->lastName   : null;
			$this->email      = isset($data->email     ) ? $data->email      : null;
			$this->role       = isset($data->role      ) ? $data->role       : null;
			$this->timezone   = isset($data->timezone  ) ? $data->timezone   : null;
			$this->photoUrl   = isset($data->photoUrl  ) ? $data->photoUrl   : null;
			$this->createdAt  = isset($data->createdAt ) ? $data->createdAt  : null;
			$this->modifiedAt = isset($data->modifiedAt) ? $data->modifiedAt : null;
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
