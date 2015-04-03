<?php
namespace HelpScout\model\thread;

interface ConversationThread {
	public function getType();
	public function isPublished();
	public function isDraft();
	public function isHeldForReview();
	public function hasAttachments();
	public function getState();
	public function getBody();
	public function getToList();
	public function getCcList();
	public function getBccList();
	public function getAttachments();
	public function isAssigned();
	public function isActive();
	public function isPending();
	public function isClosed();
	public function isSpam();
	public function getAssignedTo();
	public function getStatus();
	public function getActionType();
	public function getActionSourceId();
	public function getCreatedBy();
	public function getFromMailbox();
	public function getObjectVars();

	public function setId($id);
	public function setType($type);
	public function setState($state);
	public function setBody($body);
	public function setToList($toList);
	public function setCcList($ccList);
	public function setBccList($bccList);
	public function setAttachments($attachments);
	public function setAssignedTo(\HelpScout\model\ref\PersonRef $assignedTo);
	public function setStatus($status);
	public function setCreatedBy(\HelpScout\model\ref\PersonRef $createdBy);
	public function setFromMailbox(\HelpScout\model\ref\MailboxRef $mailbox);

	public function toJson();
}

abstract class AbstractThread extends LineItem implements ConversationThread {
	private $type;
	private $state;
	private $body;
	private $toList;
	private $ccList;
	private $bccList;
	private $customer;

	// only available on Message threads. Indicates when the customer viewed the message.
	protected $openedAt = null;

	private $attachments;

	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {
			$this->body    = isset($data->body)    ? $data->body    : null;
			$this->toList  = isset($data->toList)  ? $data->toList  : null;
			$this->ccList  = isset($data->ccList)  ? $data->ccList  : null;
			$this->bccList = isset($data->bccList) ? $data->bccList : null;
			$this->state   = isset($data->state)   ? $data->state   : null;
			$this->type    = isset($data->type)    ? $data->type    : null;
			$this->openedAt= isset($data->openedAt)? $data->openedAt: null;

			if ($data->customer) {
				$this->customer = new \HelpScout\model\ref\PersonRef($data->customer);
			}

			if ($data->attachments) {
				$this->attachments = array();
				foreach($data->attachments as $at) {
					$this->attachments[] = new \HelpScout\model\Attachment($at);
				}
			}
		}
	}

	public function getObjectVars() {
		$vars = array();
		$vars['id'] = $this->getId();
		$vars['status'] = $this->getStatus();
		$vars['createdAt'] = $this->getCreatedAt();

		if ($this->isAssigned()) {
			$assignedTo = $this->getAssignedTo();
			if (!$assignedTo) {
				throw new \HelpScout\ApiException('No assignedTo (\HelpScout\model\ref\PersonRef) object set in AbstractThread.getObjectVars() method.');
			}
			$vars['assignedTo'] = $assignedTo->getObjectVars();
		}

		$createdBy = $this->getCreatedBy();
		if (!$createdBy) {
			throw new \HelpScout\ApiException('No createdBy (\HelpScout\model\ref\PersonRef) object set in AbstractThread.getObjectVars() method.');
		}
		$vars['createdBy'] = $createdBy->getObjectVars();

		if ($this->getFromMailbox() != null) {
			$vars['fromMailbox'] = $this->getFromMailbox()->getObjectVars();
		}

		if ($this->getType() == null) {
			if ($this instanceof \HelpScout\model\thread\Customer) {
				$this->type = 'customer';
			} else if ($this instanceof \HelpScout\model\thread\Message) {
				$this->type = 'message';
			} else if ($this instanceof \HelpScout\model\thread\Note) {
				$this->type = 'note';
			} else if ($this instanceof \HelpScout\model\thread\Chat) {
				$this->type = 'chat';
			} else if ($this instanceof \HelpScout\model\thread\Phone) {
				$this->type = 'phone';
			} else if ($this instanceof \HelpScout\model\thread\ForwardChild) {
				$this->type = 'forwardchild';
			} else if ($this instanceof \HelpScout\model\thread\ForwardParent) {
				$this->type = 'forwardparent';
			} else {
				$this->type = 'lineitem';
			}
		}

		$vars['type'] = $this->getType();
		$vars['state'] = $this->getState();
		$vars['body'] = $this->getBody();
		$vars['to'] = $this->getToList();
		$vars['cc'] = $this->getCcList();
		$vars['bcc'] = $this->getBccList();

		if ($this->getCustomer() != null) {
			$vars['customer'] = $this->getCustomer()->getObjectVars();
		}
		$this->addAttachmentsToVars($vars);

		return $vars;
	}

	private function addAttachmentsToVars(array &$vars) {
		// Attachments
		$list = $this->getAttachments();
		if ($list) {
			$attachments = array();
			foreach($list as $attachment) {
                /* @var $attachment \HelpScout\model\Attachment */
				$attachments[] = $attachment->getObjectVars();
			}
			$vars['attachments'] = $attachments;
		}
	}

	public function toJson() {
		$vars = $this->getObjectVars();
		return json_encode($vars);
	}

	/**
	 * @param $attachments
	 */
	public function setAttachments($attachments) {
		$this->attachments = $attachments;
	}

	public function addAttachment(\HelpScout\model\Attachment $attachment) {
		if (!$this->attachments) {
			$this->attachments = array();
		}
		$this->attachments[] = $attachment;
	}

	/**
	 * @param $bccList
	 */
	public function setBccList($bccList) {
		$this->bccList = $bccList;
	}

	/**
	 * @param $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}

	/**
	 * @param $ccList
	 */
	public function setCcList($ccList) {
		$this->ccList = $ccList;
	}

	/**
	 * @param $customer
	 */
	public function setCustomer($customer) {
		$this->customer = $customer;
	}

	/**
	 * @param $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @param $toList
	 */
	public function setToList($toList) {
		$this->toList = $toList;
	}

	/**
	 * @param $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return bool
	 */
	public function isPublished() {
		return $this->state == 'published';
	}

	/**
	 * @return bool
	 */
	public function isDraft() {
		return $this->state == 'draft';
	}

	/**
	 * @return bool
	 */
	public function isHeldForReview() {
		return $this->state == 'underreview';
	}

	/**
	 * @return bool
	 */
	public function hasAttachments() {
		return $this->attachments && count($this->attachments) > 0;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @return mixed
	 */
	public function getToList() {
		return $this->toList;
	}

	/**
	 * @return mixed
	 */
	public function getCcList() {
		return $this->ccList;
	}

	/**
	 * @return mixed
	 */
	public function getBccList() {
		return $this->bccList;
	}

	/**
	 * @return array
	 */
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * @return \HelpScout\model\ref\PersonRef
	 */
	public function getCustomer() {
		return $this->customer;
	}
}
