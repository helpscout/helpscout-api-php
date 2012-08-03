<?php 
namespace HelpScout\model\ref;

abstract class AbstractRef {	
	private $id        = false;
	private $firstName = false;
	private $lastName  = false;
	private $email     = false;
	
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
}