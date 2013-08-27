<?php
namespace HelpScout\model\ref;

class MailboxRef {
	private $id;
	private $name;

	public function __construct($data=null) {
		if ($data) {
			$this->id   = isset($data->id)   ? $data->id   : null;
			$this->name = isset($data->name) ? $data->name : null;
		}
	}

	/**
	 * @return array
	 */
	public function getObjectVars() {
		return get_object_vars($this);
	}

	/**
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}
}
