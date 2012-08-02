<?php
namespace HelpScout\model\customer;

class Address extends Object {

	private $customerID;
	private $city;
	private $state;
	private $postalCode;
	private $country;
	private $lines = null;
	private $createdAt;
	private $modifiedAt;
	
	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {
			$this->customerId = $data->customerId;
			$this->city = $data->city;
			$this->state = $data->state;
			$this->postalCode = $data->postalCode;
			$this->country = $data->country;
			$this->lines = $data->lines;
			$this->createdAt = $data->createdAt;
			$this->modifiedAt = $data->modifiedAt;
		}
	}

	public function getFullAddress() {
		return implode(', ', $this->getLocationParts());
	}

	private function getLocationParts() {
		if ($this->lines !== null) {
			$parts = explode(PHP_EOL, $this->lines);
		} else {
			$parts = array();
		}	
		if (isset($this->city)) {
			$parts[] = $this->city;
		}
		if (isset($this->state)) {
			$parts[] = $this->state;
		}
		if (isset($this->country)) {
			$parts[] = $this->country;
		}
		return $parts;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $customerID
	 */
	public function getCustomerID() {
		return $this->customerID;
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
