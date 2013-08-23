<?php
namespace HelpScout\model\customer;

class Address {
	private $id;
	private $lines;
	private $city;
	private $state;
	private $postalCode;
	private $country;
	private $createdAt;
	private $modifiedAt;

	public function __construct($data=null) {
		if ($data) {
			$this->id        = isset($data->id)         ? $data->id         : null;
			$this->lines     = isset($data->lines)      ? $data->lines      : null;
			$this->city      = isset($data->city)       ? $data->city       : null;
			$this->state     = isset($data->state)      ? $data->state      : null;
			$this->postalCode= isset($data->postalCode) ? $data->postalCode : null;
			$this->country   = isset($data->country)    ? $data->country    : null;
			$this->createdAt = isset($data->createdAt)  ? $data->createdAt  : null;
			$this->modifiedAt= isset($data->modifiedAt) ? $data->modifiedAt : null;
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
