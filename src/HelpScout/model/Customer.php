<?php
namespace HelpScout\model;

class Customer {
	private $id;
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

	private $background;
	private $address;
	private $socialProfiles;
	private $emails;
	private $phones;
	private $chats;
	private $websites;

	public function __construct($data=null) {
		if ($data) {
			$this->id           = isset($data->id)           ? $data->id           : null;
			$this->firstName    = isset($data->firstName)    ? $data->firstName    : null;
			$this->lastName     = isset($data->lastName)     ? $data->lastName     : null;
			$this->photoUrl     = isset($data->photoUrl)     ? $data->photoUrl     : null;
			$this->photoType    = isset($data->photoType)    ? $data->photoType    : null;
			$this->gender       = isset($data->gender)       ? $data->gender       : null;
			$this->age          = isset($data->age)          ? $data->age          : null;
			$this->organization = isset($data->organization) ? $data->organization : null;
			$this->jobTitle     = isset($data->jobTitle)     ? $data->jobTitle     : null;
			$this->location     = isset($data->location)     ? $data->location     : null;
			$this->createdAt    = isset($data->createdAt)    ? $data->createdAt    : null;
			$this->modifiedAt   = isset($data->modifiedAt)   ? $data->modifiedAt   : null;
			$this->background   = isset($data->background)   ? $data->background   : null;

			if (isset($data->address)) {
				$this->address = new \HelpScout\model\customer\Address($data->address);
			}
			if (isset($data->chats)) {
				$this->chats = $this->toList($data->chats, '\HelpScout\model\customer\ChatEntry');
			}
			if (isset($data->emails)) {
				$this->emails = $this->toList($data->emails, '\HelpScout\model\customer\EmailEntry');
			}
			if (isset($data->phones)) {
				$this->phones = $this->toList($data->phones, '\HelpScout\model\customer\PhoneEntry');
			}
			if (isset($data->socialProfiles)) {
				$this->socialProfiles = $this->toList($data->socialProfiles, '\HelpScout\model\customer\SocialProfileEntry');
			}
			if (isset($data->websites)) {
				$this->websites = $this->toList($data->websites, '\HelpScout\model\customer\WebsiteEntry');
			}
		}
	}

    public function toJSON() {
        $vars = get_object_vars($this);

        $vars['emails']         = $this->listToJson($this->getEmails());
        $vars['socialProfiles'] = $this->listToJson($this->getSocialProfiles());
        $vars['websites']       = $this->listToJson($this->getWebsites());
        $vars['chats']          = $this->listToJson($this->getChats());
        $vars['phones']         = $this->listToJson($this->getPhones());

        $vars['address'] = null;
        $addy = $this->getAddress();
        if ($addy) {
        	$vars['address'] = $addy->getObjectVars();
        }
        return json_encode($vars);
    }

    private function listToJson($list) {
    	$temp = array();
    	if ($list) {
	    	foreach($list as $obj) {
	    		$temp[] = $obj->getObjectVars();
	    	}
    	}
    	return $temp;
    }

	private function toList($jsonList, $type) {
		if (!$jsonList) {
			return null;
		}
		$list = array();
		array_walk($jsonList,
			function($obj) use (&$list, $type) {
				$list[] = new $type($obj);
			}
		);
		return $list;
	}

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setBackground($background) {
        $this->background = $background;
    }

    public function setChats($chats) {
        $this->chats = $chats;
    }

    public function addChat(\HelpScout\model\customer\ChatEntry $chat) {
    	if (!$this->chats) {
    		$this->chats = array();
    	}
    	$this->chats[] = $chat;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setEmails($emails) {
        $this->emails = $emails;
    }

    public function addEmail(\HelpScout\model\customer\EmailEntry $email) {
    	if (!$this->emails) {
    		$this->emails = array();
    	}
    	$this->emails[] = $email;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }

    public function setPhones($phones) {
        $this->phones = $phones;
    }

    public function addPhone(\HelpScout\model\customer\PhoneEntry $phone) {
    	if (!$this->phones) {
    		$this->phones = array();
    	}
    	$this->phones[] = $phone;
    }

    public function setPhotoType($photoType) {
        $this->photoType = $photoType;
    }

    public function setPhotoUrl($photoUrl) {
        $this->photoUrl = $photoUrl;
    }

    public function addSocialProfile(\HelpScout\model\customer\SocialProfileEntry $profile) {
    	if (!$this->socialProfiles) {
    		$this->socialProfiles = array();
    	}
    	$this->socialProfiles[] = $profile;
    }

    public function setSocialProfiles($socialProfiles) {
        $this->socialProfiles = $socialProfiles;
    }

    public function setWebsites($websites) {
        $this->websites = $websites;
    }

    public function addWebsite(\HelpScout\model\customer\WebsiteEntry $website) {
    	if (!$this->websites) {
    		$this->websites = array();
    	}
    	$this->websites[] = $website;
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

	private function isEmpty($value) {
		$v = trim($value);
		return empty($v);
	}

	/**
	 * @return \HelpScout\model\ref\CustomerRef
	 */
	public function toRef() {
		$ref = new \HelpScout\model\ref\CustomerRef();
		$ref->setId       ($this->id);
		$ref->setFirstName($this->firstName);
		$ref->setLastName ($this->lastName);

		return $ref;
	}
}