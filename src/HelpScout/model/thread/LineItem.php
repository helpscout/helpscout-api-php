<?php
namespace HelpScout\model\thread;

use HelpScout\model\Conversation;

class LineItem {
	const STATUS_NOCHANGE= 'nochange';
	const STATUS_ACTIVE  = 'active';
	const STATUS_PENDING = 'pending';
	const STATUS_CLOSED  = 'closed';
	const STATUS_SPAM    = 'spam';

	private $id = null;
	private $type;
	private $status;
	private $actionType;
	private $actionSourceId;
	private $createdAt;

	/**
	 * @var \HelpScout\model\ref\PersonRef
	 */
	private $assignedTo;

	/**
	 * @var \HelpScout\model\ref\PersonRef
	 */
	private $createdBy;

	/**
	 * @var \HelpScout\model\ref\MailboxRef
	 */
	private $fromMailbox;

	public function __construct($data=null) {
		$this->type   = 'lineitem';
		$this->status = self::STATUS_ACTIVE;

		if ($data) {
			$this->id             = isset($data->id)             ? $data->id             : null;
			$this->type           = isset($data->type)           ? $data->type           : null;
			$this->status         = isset($data->status)         ? $data->status         : null;
			$this->actionType     = isset($data->actionType)     ? $data->actionType     : null;
			$this->actionSourceId = isset($data->actionSourceId) ? $data->actionSourceId : null;
			$this->createdAt      = isset($data->createdAt)      ? $data->createdAt      : null;
			$this->createdBy      = new \HelpScout\model\ref\PersonRef($data->createdBy);

			if ($data->fromMailbox) {
				$this->fromMailbox = new \HelpScout\model\ref\MailboxRef($data->fromMailbox);
			}
			if ($data->assignedTo) {
				$this->assignedTo = new \HelpScout\model\ref\PersonRef($data->assignedTo);
			}
		}
	}

	public function getObjectVars() {
		$vars                   = array();
		$vars['id']             = $this->getId();
		$vars['type']           = $this->getType();
		$vars['status']         = $this->getStatus();
		$vars['actionType']     = $this->getActionType();
		$vars['actionSourceId'] = $this->getActionSourceId();
		$vars['createdAt']      = $this->getCreatedAt();

		if ($this->getAssignedTo() != null) {
			$vars['assignedTo'] = $this->getAssignedTo()->getObjectVars();
		}

		if ($this->getCreatedBy() != null) {
			$vars['createdBy'] = $this->getCreatedBy()->getObjectVars();
		}

		if ($this->getFromMailbox() != null) {
			$vars['fromMailbox'] = $this->getFromMailbox()->getObjectVars();
		}
	}

	/**
	 * @param \HelpScout\model\ref\PersonRef $assignedTo
	 */
	public function setAssignedTo(\HelpScout\model\ref\PersonRef $assignedTo) {
		$this->assignedTo = $assignedTo;
	}

	/**
	 * @param $createdAt
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @param \HelpScout\model\ref\PersonRef $createdBy
	 */
	public function setCreatedBy(\HelpScout\model\ref\PersonRef $createdBy) {
		$this->createdBy = $createdBy;
	}

	/**
	 * @param \HelpScout\model\ref\MailboxRef $fromMailbox
	 */
	public function setFromMailbox(\HelpScout\model\ref\MailboxRef $fromMailbox) {
		$this->fromMailbox = $fromMailbox;
	}

	/**
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return bool
	 */
	public function isAssigned() {
		return is_object($this->assignedTo) && $this->assignedTo->getId() > Conversation::OWNER_ANYONE;
	}

	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->status == self::STATUS_ACTIVE;
	}

	/**
	 * @return bool
	 */
	public function isPending() {
		return $this->status == self::STATUS_PENDING;
	}

	/**
	 * @return bool
	 */
	public function isClosed() {
		return $this->status == self::STATUS_CLOSED;
	}

	/**
	 * @return bool
	 */
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
	 * @return mixed
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

	public function getActionSourceId() {
		return $this->actionSourceId;
	}

	public function getActionType() {
		return $this->actionType;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}
}
