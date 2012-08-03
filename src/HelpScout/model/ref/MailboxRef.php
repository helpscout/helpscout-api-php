<?php 
namespace HelpScout\model\ref;
use HelpScout\model\Object;

class MailboxRef extends Object {
	
	private $name = false;
	private $email = false;
	
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
?>