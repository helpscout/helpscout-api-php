<?php 
namespace HelpScout\model\ref;

abstract class AbstractRef {

    const TYPE_USER = "user";
    const TYPE_CUSTOMER = "customer";

	private $id        = false;
	private $firstName = false;
	private $lastName  = false;
	private $email     = false;
    private $type      = false;
	
	public function __construct($data=null, $type="user") {
		if ($data) {
			$this->id        = $data->id;
			$this->firstName = $data->firstName;
			$this->lastName  = $data->lastName;
			$this->email     = $data->email;
            $this->type      = $type;
		}
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $firstName
	 */
	public function getFirstName() {
		return $this->firstName;
	}
	/**
	 * @return the $lastName
	 */
	public function getLastName() {
		return $this->lastName;
	}
	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

    /**
     * @return the $type
     */
    public function getType() {
        return $this->type;
    }
}