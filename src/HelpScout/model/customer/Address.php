<?php
namespace HelpScout\model\customer;

class Address {
	private $id    = null;
	private $lines = null;
	private $city;
	private $state;
	private $postalCode;
	private $country;
	private $createdAt;
	private $modifiedAt;
	
	public function __construct($data=null) {		
		if ($data) {
			$this->id         = $data->id;
			$this->lines      = $data->lines;
			$this->city       = $data->city;
			$this->state      = $data->state;
			$this->postalCode = $data->postalCode;
			$this->country    = $data->country;
			$this->createdAt  = $data->createdAt;
			$this->modifiedAt = $data->modifiedAt;
		}
	}

    public function getObjectVars() {
        return get_object_vars($this);
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return array
	 */
	public function getLines() {
		return $this->lines;
	}
	
	/**
	 * @return the $city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return the $postalCode
	 */
	public function getPostalCode() {
		return $this->postalCode;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return the $createdAt
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @return the $modifiedAt
	 */
	public function getModifiedAt() {
		return $this->modifiedAt;
	}
}
