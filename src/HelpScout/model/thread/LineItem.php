<?php
namespace HelpScout\model\thread;

use HelpScout\model\Conversation;

class LineItem {
	const STATUS_NOCHANGE= 'nochange';
	const STATUS_ACTIVE  = 'active';
	const STATUS_PENDING = 'pending';
	const STATUS_CLOSED  = 'closed';
	const STATUS_SPAM    = 'spam';
	
	private $id = false;
	private $assignedTo;
	private $status;
	private $createdBy;
	private $fromMailbox;
	
	public function __construct($data=null) {		
		if ($data) {
			$this->id            = $data->id;
			$this->assignedTo    = $data->assignedTo;
			$this->status        = $data->status;
			$this->createdAt     = $data->createdAt;
            $this->createdBy = new \HelpScout\model\ref\PersonRef($data->createdBy);

			if ($data->fromMailbox) {
				$this->fromMailbox = new \HelpScout\model\ref\MailboxRef($data->fromMailbox);
			}
			if ($data->assignedTo) {
				$this->assignedTo = new \HelpScout\model\ref\PersonRef($data->assignedTo);
			}
		}
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	public function isAssigned() {
		return is_numeric($this->assignedTo) && $this->assignedTo > Conversation::OWNER_ANYONE;
	}
	
	public function isActive() {
		return $this->status == self::STATUS_ACTIVE;
	}
	
	public function isPending() {
		return $this->status == self::STATUS_PENDING;
	}
	
	public function isClosed() {
		return $this->status == self::STATUS_CLOSED;
	}
	
	public function isSpam() {
		return $this->status == self::STATUS_SPAM;
	}
	
	/**
	 * @return \HelpScout\model\ref\PersonRef
	 */
	public function getAssignedTo() {
		return $this->assignedTo;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return \HelpScout\model\ref\PersonRef
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @return \HelpScout\model\ref\MailboxRef
	 */
	public function getFromMailbox() {
		return $this->fromMailbox;
	}
}
