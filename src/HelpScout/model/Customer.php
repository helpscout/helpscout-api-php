<?php
namespace HelpScout\model;

class Customer extends Object {
	private $firstName;
	private $lastName;
	private $photoUrl;
	private $photoType;
	private $gender;
	private $age;
	private $organization;
	private $jobTitle;
	private $location;
	private $createdAt;
	private $modifiedAt;

	private $background = false;
	private $address = false;
	private $socialProfiles = false;
	private $emails = false;
	private $phones = false;
	private $chats = false;
	private $websites = false;
	
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
	public function getPhotoType() {
		return $this->photoType;
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	private $email;
	}

	/**
	 * @return string
	 */
	public function getAge() {
		return $this->age;
	}

	/**
	 * @return string
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * @return string
	 */
	public function getJobTitle() {
		return $this->jobTitle;
	}

	/**
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
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

