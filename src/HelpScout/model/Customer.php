<?php
namespace HelpScout\model;

use HelpScout\model\customer\EmailEntry;

use HelpScout\model\customer\CustomerEntry;

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

	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {
			$this->firstName = $data->firstName;
			$this->lastName = $data->lastName;
			$this->photoUrl = $data->photoUrl;
			$this->photoType = $data->photoType;
			$this->gender = $data->gender;
			$this->age = $data->age;
			$this->organization = $data->organization;
			$this->jobTitle = $data->jobTitle;
			$this->location = $data->location;
			$this->createdAt = $data->createdAt;
			$this->modifiedAt = $data->modifiedAt;
			
			if (isset($data->background)) {
				$this->background = $data->background;
			}
			
			if (isset($data->address)) {
				$this->address = new \HelpScout\model\customer\Address($data->address);
			}
	
			if (isset($data->chats)) {
				$this->chats = array();
				foreach($data->chats as $chat) {
					$this->chats[] = new \HelpScout\model\customer\ChatEntry($chat);
				}
			}
			
			if (isset($data->emails)) {
				$this->emails = array();
				foreach($data->emails as $email) {
					$this->emails[] = new \HelpScout\model\customer\EmailEntry($email);
				}
			}
			
			if (isset($data->phones)) {
				$this->phones = array();
				foreach($data->phones as $phone) {
					$this->phones[] = new \HelpScout\model\customer\PhoneEntry($phone);
				}
			}
			
			if (isset($data->socialProfiles)) {
				$this->socialProfiles = array();
				foreach($data->socialProfiles as $profile) {
					$this->socialProfiles[] = new \HelpScout\model\customer\SocialProfileEntry($profile);
				}
			}
			
			if (isset($data->websites)) {
				$this->websites = array();
				foreach($data->websites as $website) {
					$this->websites[] = new \HelpScout\model\customer\WebsiteEntry($website);
				}
			}
		}
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
	
	/**
	 * @return the $background
	 */
	public function getBackground() {
		return $this->background;
	}
	
	/**
	 * @return the $address
	 */
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * @return the $socialProfiles
	 */
	public function getSocialProfiles() {
		return $this->socialProfiles;
	}
	
	/**
	 * @return the $emails
	 */
	public function getEmails() {
		return $this->emails;
	}
	
	/**
	 * @return the $phones
	 */
	public function getPhones() {
		return $this->phones;
	}
	
	/**
	 * @return the $chats
	 */
	public function getChats() {
		return $this->chats;
	}
	
	/**
	 * @return the $websites
	 */
	public function getWebsites() {
		return $this->websites;
	}
}

