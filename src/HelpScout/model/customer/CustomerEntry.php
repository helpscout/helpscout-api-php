<?php
namespace HelpScout\model\customer;

class CustomerEntry {
	private $id;
	private $value;
	private $type;
	private $location;

	public function __construct($data=null) {
		if ($data) {
			$this->id       = isset($data->id)       ? $data->id       : null;
			$this->value    = isset($data->value)    ? $data->value    : null;
			$this->type     = isset($data->type)     ? $data->type     : null;
			$this->location = isset($data->location) ? $data->location : null;
		}
	}

    public function getObjectVars() {
        return get_object_vars($this);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setValue($value) {
        $this->value = $value;
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