<?php
namespace HelpScout\model;

class Object {
	protected $id = false;

	public function __construct($data=null) {
		if ($data) {
			$this->id = isset($data->id) ? $data->id : false;			
		}		
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param boolean $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	protected final function isEmpty($value) {
		$v = trim($value);
		return empty($v);
	}
}