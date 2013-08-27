<?php
namespace HelpScout\model;

class Mailbox {
	private $id = false;
	private $name;
	private $slug;
	private $email;
	private $createdAt;
	private $modifiedAt;

	private $folders = false;

	public function __construct($data=null) {
		if ($data) {
			$this->id         = isset($data->id)         ? $data->id         : null;
			$this->name       = isset($data->name)       ? $data->name       : null;
			$this->slug       = isset($data->slug)       ? $data->slug       : null;
			$this->email      = isset($data->email)      ? $data->email      : null;
			$this->createdAt  = isset($data->createdAt)  ? $data->createdAt  : null;
			$this->modifiedAt = isset($data->modifiedAt) ? $data->modifiedAt : null;
		}
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

	/**
	 * @return the $slug
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return the $createdAt
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @return the $modifiedAt
	 */
	public function getModifiedAt() {
		return $this->modifiedAt;
	}

	/**
	 * @return the $folders
	 */
	public function getFolders($cache=true) {
		if ($this->folders === false) {
			$folders = \HelpScout\ApiClient::getInstance()->getFolders($this->id);
			if ($folders) {
				if ($cache) {
					$this->folders = $folders->getItems();
				} else {
					return $folders->getItems();
				}
			}
		}
		return $this->folders;
	}

	public function setFolders(array $folders) {
		$this->folders = $folders;
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	public function getUnassignedFolder() {
		return $this->getFolderByType('unassigned');
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	public function getAssignedFolder() {
		return $this->getFolderByType('assigned');
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	public function getMyTicketsFolder() {
		return $this->getFolderByType('mytickets');
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	public function getDraftsFolder() {
		return $this->getFolderByType('drafts');
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	public function getClosedFolder() {
		return $this->getFolderByType('closed');
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	public function getSpamFolder() {
		return $this->getFolderByType('spam');
	}

	/**
	 * @return \HelpScout\model\Folder
	 */
	private function getFolderByType($type) {
		$folders = $this->getFolders();
		if (!$folders) {
			return false;
		}
		$theFolder = false;

		foreach($folders as $folder) {
			if ($folder->getType() == $type) {
				$theFolder = $folder;
				break;
			}
		}
		return $theFolder;
	}

	/**
	 * @param boolean $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param boolean $slug
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
	}

	/**
	 * @param boolean $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param boolean $createdAt
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @param boolean $modifiedAt
	 */
	public function setModifiedAt($modifiedAt) {
		$this->modifiedAt = $modifiedAt;
	}

	/**
	 * Get MailboxRef for the current Mailbox object
	 *
	 * @return \HelpScout\model\ref\MailboxRef
	 */
	public function toRef() {
		$ref = new \HelpScout\model\ref\MailboxRef();
		$ref->setId($this->getId());
		$ref->setName($this->getName());

		return $ref;
	}
}
