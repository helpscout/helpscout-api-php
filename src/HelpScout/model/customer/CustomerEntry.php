<?php
namespace HelpScout\model\customer;

class CustomerEntry {
	private $id = false;
	private $value;
	private $type = null;
	private $location = null;
	
	public function __construct($data=null) {		
		if ($data) {
			$this->id    = $data->id;
			$this->value = $data->value;
			if (isset($data->type)) {
				$this->type = $data->type;
			}
			if (isset($data->location)) {
				$this->location = $data->location;
			}
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
	public function getValue() {
		return $this->value;
	}
	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}
	/**
	 * @return the $location
	 */
	public function getLocation() {
		return $this->location;
	}


}