<?php 
namespace HelpScout\model\ref;

class MailboxRef {
	private $id    = null;
	private $name  = null;
		
	public function __construct($data=null) {
		if ($data) {
			$this->id   = $data->id;
			$this->name = $data->name;			
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