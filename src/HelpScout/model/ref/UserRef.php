<?php 
namespace HelpScout\model\ref;
use HelpScout\model\Object;

class UserRef extends Object {
	
	private $firstName = false;
	private $lastName = false;
	private $email = false;
	
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
}
?>