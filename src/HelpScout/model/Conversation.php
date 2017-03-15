<?php
namespace HelpScout\model;

use HelpScout\CustomFieldFactory;

class Conversation {
	const STATUS_ACTIVE  = 'active';
	const STATUS_PENDING = 'pending';
	const STATUS_CLOSED  = 'closed';
	const STATUS_SPAM    = 'spam';

	const OWNER_ANYONE = 1;

	private $id             = null;
	private $type           = null;
	private $folderId       = 0;
	private $draft          = null;
	private $number         = 0;
	private $owner          = null;
	private $mailbox        = null;
	private $customer       = null;
	private $threadCount    = 0;
	private $status         = null;
	private $subject        = null;
	private $preview        = null;
	private $createdBy      = null;
	private $createdByType  = null;
	private $createdAt      = null;
	private $modifiedAt     = null;
	private $userModifiedAt = null;
	private $closedAt       = null;
	private $closedBy       = null;
	private $source         = null;
	private $ccList         = null;
	private $bccList        = null;
	private $tags           = null;
	private $threads        = null;
	private $customFields   = null;

	private $unassigned     = false;

	public function __construct($data = null) {
		$this->status = self::STATUS_ACTIVE;
		$this->type   = 'email';

		if ($data) {
			$this->id       = isset($data->id)       ? $data->id       : null;
			$this->type     = isset($data->type)     ? $data->type     : null;
			$this->folderId = isset($data->folderId) ? $data->folderId : null;
			$this->draft    = isset($data->isDraft)  ? $data->isDraft  : null;
			$this->number   = isset($data->number)   ? $data->number   : null;

			if (isset($data->owner)) {
				$this->owner = new \HelpScout\model\ref\PersonRef($data->owner);
			}

			if (isset($data->mailbox)) {
				$this->mailbox = new \HelpScout\model\ref\MailboxRef($data->mailbox);
			}

			if (isset($data->customer)) {
				$this->customer = new \HelpScout\model\ref\PersonRef($data->customer);
			}

			$this->source      = isset($data->source) ? $data->source : null;
			$this->threadCount = isset($data->threadCount) ? $data->threadCount : null;
			$this->status      = isset($data->status) ? $data->status : null;
			$this->subject     = isset($data->subject) ? $data->subject : null;
			$this->preview     = isset($data->preview) ? $data->preview : null;
			$this->createdBy   = new \HelpScout\model\ref\PersonRef($data->createdBy);

			$this->createdAt   = isset($data->createdAt) ? $data->createdAt : null;
			$this->modifiedAt  = isset($data->modifiedAt) ? $data->modifiedAt : null;
			$this->userModifiedAt  = isset($data->userModifiedAt) ? $data->userModifiedAt : null;
			$this->closedAt    = isset($data->closedAt) ? $data->closedAt : null;
			$this->ccList      = isset($data->cc) ? $data->cc : null;
			$this->bccList     = isset($data->bcc) ? $data->bcc : null;
			$this->tags        = isset($data->tags) ? $data->tags : null;

			if ($data->closedBy) {
				$this->closedBy = new \HelpScout\model\ref\PersonRef($data->closedBy);
			}

			if (isset($data->threads)) {
				$this->threads = array();
				$types = array(
					'lineitem'      => '\HelpScout\model\thread\LineItem',
					'customer'      => '\HelpScout\model\thread\Customer',
					'message'       => '\HelpScout\model\thread\Message',
					'note'          => '\HelpScout\model\thread\Note',
					'forwardparent' => '\HelpScout\model\thread\ForwardParent',
					'forwardchild'  => '\HelpScout\model\thread\ForwardChild',
					'chat'          => '\HelpScout\model\thread\Chat',
					'phone'         => '\HelpScout\model\thread\Phone'
				);
				foreach ($data->threads as $thread) {
					if (is_null($thread)) {
						continue;
					}
					$type = $thread->type;
					if (isset($types[$type])) {
						$this->threads[] = new $types[$type]($thread);
					} else {
						throw new \HelpScout\ApiException('Unknown thread type [' . $type . ']');
					}
				}
			}

			if (isset($data->customFields)) {
				$this->customFields = array();

				foreach ($data->customFields as $field) {
					$this->customFields[] = CustomFieldFactory::fromConversation((array) $field);
				}
			}
		}
	}

	public function getObjectVars() {
		$vars = array();
		$vars['id'] = $this->getId();
		$vars['type'] = $this->getType();
		$vars['folderId'] = $this->getFolderId();
		$vars['draft'] = $this->isDraft();
		$vars['status'] = $this->getStatus();
		$vars['subject'] = $this->getSubject();
		$vars['createdAt'] = $this->getCreatedAt();
		$vars['modifiedAt'] = $this->getModifiedAt();
		$vars['userModifiedAt'] = $this->getUserModifiedAt();
		$vars['closedAt'] = $this->getClosedAt();
		$vars['source'] = $this->getSource();
		$vars['cc'] = $this->getCcList();
		$vars['bcc'] = $this->getBccList();
		$vars['tags'] = $this->getTags();

		if ($this->getOwner() != null) {
			$vars['owner'] = $this->getOwner()->getObjectVars();
		}

		if (is_null($this->getOwner()) && $this->unassigned) {
			$vars['owner'] = null;
		}

		if ($this->getCustomer() != null) {
			$vars['customer'] = $this->getCustomer()->getObjectVars();
		}

		$mailbox = $this->getMailbox();
		if (!$mailbox) {
			throw new \HelpScout\ApiException('No mailbox (\HelpScout\model\ref\MailboxRef) object set in Conversation.getObjectVars() method.');
		}
		$vars['mailbox'] = $mailbox->getObjectVars();
		unset($mailbox);

		$createdBy = $this->getCreatedBy();
		if (!$createdBy) {
			throw new \HelpScout\ApiException('No createdBy (\HelpScout\model\ref\PersonRef) object set in Conversation.getObjectVars() method.');
		}
		$vars['createdBy'] = $createdBy->getObjectVars();

		if ($this->isClosed()) {
			$closedBy = $this->getClosedBy();
			if ($closedBy) {
				$vars['closedBy'] = $closedBy->getObjectVars();
			} else {
				$vars['closedBy'] = null;
			}
		}
		$this->addThreadsToVars($vars);

		$this->addCustomFieldsToVars($vars);

		return $vars;
	}

	private function addThreadsToVars(array &$vars) {
		/* @var $thread \HelpScout\model\thread\AbstractThread */
		$threads = array();

		$list = $this->getThreads();
		if ($list) {
			foreach($list as $thread) {
				$threads[] = $thread->getObjectVars();
			}
		}
		$vars['threads'] = $threads;
	}

	private function addCustomFieldsToVars(array &$vars) {
		/* @var $field \HelpScout\model\ref\customfields\AbstractCustomFieldRef */
		$fields = array();

		if ($list = $this->getCustomFields()) {
			foreach ($list as $field) {
				$fields[] = array(
					'fieldId' => $field->getId(),
					'name' => $field->getName(),
					'value' => $field->getValue()
				);
			}
		}
		$vars['customFields'] = $fields;
	}

	public function toJSON() {
		$vars = $this->getObjectVars();
		return json_encode($vars);
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

	public function setBccList($bccList) {
		$this->bccList = $bccList;
	}

	public function setCcList($ccList) {
		$this->ccList = $ccList;
	}

	public function setClosedAt($closedAt) {
		$this->closedAt = $closedAt;
	}

	/**
	 * @param $closedBy
	 */
	public function setClosedBy(\HelpScout\model\ref\PersonRef $closedBy) {
		$this->closedBy = $closedBy;
	}

	/**
	 * @param $createdAt
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @param ref\PersonRef $createdBy
	 */
	public function setCreatedBy(\HelpScout\model\ref\PersonRef $createdBy) {
		$this->createdBy = $createdBy;
	}

	/**
	 * @param $createdByType
	 */
	public function setCreatedByType($createdByType) {
		$this->createdByType = $createdByType;
	}

	/**
	 * @param ref\PersonRef $customer
	 */
	public function setCustomer(\HelpScout\model\ref\PersonRef $customer) {
		$this->customer = $customer;
	}

	/**
	 * @param $draft
	 */
	public function setDraft($draft) {
		$this->draft = $draft;
	}

	/**
	 * @param $folderId
	 */
	public function setFolderId($folderId) {
		$this->folderId = $folderId;
	}

	/**
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param ref\MailboxRef $mailbox
	 */
	public function setMailbox(\HelpScout\model\ref\MailboxRef $mailbox) {
		$this->mailbox = $mailbox;
	}

	/**
	 * @param $userModifiedAt
	 */
	public function setUserModifiedAt($userModifiedAt) {
		$this->modifiedAt = $userModifiedAt;
		$this->userModifiedAt = $userModifiedAt;
	}

	/**
	 * @param $userModifiedAt
	 */
	public function setModifiedAt($userModifiedAt) {
        $this->setUserModifiedAt($userModifiedAt);
	}

	/**
	 * @param $number
	 */
	public function setNumber($number) {
		$this->number = $number;
	}

	/**
	 * @param ref\PersonRef $owner
	 */
	public function setOwner(\HelpScout\model\ref\PersonRef $owner) {
		$this->owner = $owner;
		$this->unassigned = false;
	}

	public function unassign() {
		$this->unassigned = true;
		$this->owner = null;
	}

	/**
	 * @param $preview
	 */
	public function setPreview($preview) {
		$this->preview = $preview;
	}

	/**
	 * @param $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * @param $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @param $tags
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}

	/**
	 * @param $threadCount
	 */
	public function setThreadCount($threadCount) {
		$this->threadCount = $threadCount;
	}

	/**
	 * @param $threads
	 */
	public function setThreads($threads) {
		$this->threads = $threads;
	}

	/**
	 * @param $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return boolean
	 */
	public function hasTags() {
		return $this->hasList($this->tags);
	}

	/**
	 * @return boolean
	 */
	public function hasCcList() {
		return $this->hasList($this->ccList);
	}

	/**
	 * @return boolean
	 */
	public function hasBccList() {
		return $this->hasList($this->bccList);
	}

	/**
	 * @param $list
	 * @return bool
	 */
	private function hasList($list) {
		return $list && count($list) > 0;
	}

	/**
	 * @return int
	 */
	public function getFolderId() {
		return $this->folderId;
	}

	/**
	 * @return boolean
	 */
	public function isDraft() {
		return $this->draft;
	}

	/**
	 * @return int
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @return ref\PersonRef|null
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @return ref\MailboxRef|null
	 */
	public function getMailbox() {
		return $this->mailbox;
	}

	/**
	 * @return ref\PersonRef|null
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * @return int
	 */
	public function getThreadCount() {
		return $this->threadCount;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return string
	 */
	public function getPreview() {
		return $this->preview;
	}

	/**
	 * @return ref\PersonRef|null
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @return string
	 */
	public function getUserModifiedAt() {
        if (!is_null($this->userModifiedAt))
        {
            return $this->userModifiedAt;
        }

		return $this->modifiedAt;
	}

	/**
	 * @return string
	 */
	public function getModifiedAt() {
		return $this->getUserModifiedAt();
	}

	/**
	 * @return string
	 */
	public function getClosedAt() {
		return $this->closedAt;
	}

	/**
	 * @return ref\PersonRef|null
	 */
	public function getClosedBy() {
		return $this->closedBy;
	}

	/**
	 * @return array
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @return array
	 */
	public function getCcList() {
		return $this->ccList;
	}

	/**
	 * @return array
	 */
	public function getBccList() {
		return $this->bccList;
	}

	/**
	 * @return array
	 */
	public function getTags() {
		return $this->tags;
	}

	public function addLineItem(\HelpScout\model\thread\LineItem $thread) {
		if (!$this->threads) {
			$this->threads = array();
		}
		$this->threads[] = $thread;
	}

	/**
	 * @param bool $cache
	 * @param bool $apiCall
	 * @return array|null
	 */
	public function getThreads($cache = true, $apiCall = true) {
		if ($this->threads === false && $apiCall) {
			$convo = \HelpScout\ApiClient::getInstance()->getConversation($this->id);
			if ($convo) {
				if ($cache) {
					$this->threads = $convo->getThreads(false, false);
				} else {
					return $convo->getThreads(false, false);
				}
			}
		}
		return $this->threads;
	}

	/**
	 * @return array|null
	 */
	public function getCustomFields()
	{
		return $this->customFields;
	}

	/**
	 * @param array|null $customFields
	 */
	public function setCustomFields(array $customFields)
	{
		$this->customFields = $customFields;
	}
}
