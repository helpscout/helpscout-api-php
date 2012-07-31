<?php
namespace HelpScout\model;

class User extends Object {
	private $firstName;
	private $lastName;
	private $email;
	private $role;
	private $timezone;
	private $photoUrl;
	private $createdAt;
	private $modifiedAt;
	
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
}
