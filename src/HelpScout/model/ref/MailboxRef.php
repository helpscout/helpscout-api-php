<?php 
namespace HelpScout\model\ref;

class MailboxRef {
	private $id    = false;
	private $name  = false;
		
	public function __construct($data=null) {
		if ($data) {
			$this->id   = $data->id;
			$this->name = $data->name;			
		}
	}

	public function getObjectVars() {
        return get_object_vars($this);
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