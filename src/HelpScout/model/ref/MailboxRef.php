<?php 
namespace HelpScout\model\ref;

class MailboxRef {
	private $id    = false;	
	private $name  = false;
	private $email = false;
	
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
	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}
}