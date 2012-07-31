<?php
namespace HelpScout\model;

class Folder extends Object {	
	private $name;
	private $type;
	private $userId      = 0;
	private $totalCount  = 0;
	private $activeCount = 0;
	
	private $modifiedAt;
			
	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {			
			$this->name        = $data->name;
			$this->type        = $data->type;
			$this->userId      = $data->userId;
			$this->totalCount  = $data->totalCount;
			$this->activeCount = $data->activeCount;						
			$this->modifiedAt  = $data->modifiedAt;			
		}	
	}
	
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @return the $total
	 */
	public function getTotalCount() {
		return $this->totalCount;
	}

	/**
	 * @return the $active
	 */
	public function getActiveCount() {
		return $this->activeCount;
	}

	/**
	 * @return the $modifiedAt
	 */
	public function getModifiedAt() {
		return $this->modifiedAt;
	}
}