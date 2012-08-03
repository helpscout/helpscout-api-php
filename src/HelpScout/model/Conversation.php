<?php
namespace HelpScout\model;

class Conversation extends Object {	
	const OWNER_ANYONE = 1;
	
	private $folderId    = 0;
	private $draft       = false;	
	private $number      = 0;
	private $owner       = false;
	private $mailbox     = false;	
	private $customer    = false;
	private $threadCount = 0;
	private $status      = false;	
	private $subject     = false;
	private $preview     = false;
	private $createdBy   = 0;
	private $createdAt   = false;
	private $modifiedAt  = false;	
	private $closedAt    = false;
	private $closedBy    = 0;	
	private $source      = false;	
	private $ccList      = false;
	private $bccList     = false;
	private $tags        = false;	
	private $threads     = false;
	
	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {
			$this->folderId    = $data->folderId;
			$this->draft       = $data->isDraft;
			$this->number      = $data->number;
			
			if (isset($data->owner)) {
				$this->owner = new \HelpScout\model\ref\UserRef($data->owner);
			}
			
			if (isset($data->address)) {
				$this->mailbox = new \HelpScout\model\ref\MailboxRef($data->mailbox);
			}
			
			if (isset($data->customer)) {
				$this->customer = new \HelpScout\model\ref\CustomerRef($data->customer);
			}
			
			$this->threadCount = $data->threadCount;
			$this->status      = $data->status;
			$this->subject     = $data->subject;
			$this->preview     = $data->preview;
			$this->createdBy   = $data->createdBy;
			$this->createdAt   = $data->createdAt;
			$this->modifiedAt  = $data->modifiedAt;
			$this->closedAt    = $data->closedAt;
			$this->closedBy    = $data->closedBy;
			$this->source      = $data->source;
			$this->ccList      = $data->cc;
			$this->bccList     = $data->bcc;
			$this->tags        = $data->tags;
			
			if (isset($data->threads)) {
				$this->threads = array();
				$types = array(
					'lineitem'     => '\HelpScout\model\thread\LineItem',
					'customer'     => '\HelpScout\model\thread\Customer',
					'message'      => '\HelpScout\model\thread\Message',					
					'note'         => '\HelpScout\model\thread\Note',
					'forwardparent'=> '\HelpScout\model\thread\ForwardParent',
					'forwardchild' => '\HelpScout\model\thread\ForwardChild',
				);
				foreach($data->threads as $thread) {
					$item = false;
					$type = $thread->type;
					if (isset($types[$type])) {						
						$this->threads[] = new $types[$type]($thread);
					} else {
						throw new \HelpScout\ApiException('Unknown thread type [' . $type . ']');
					}					
				}
			}
		}
	}
	
	public function hasTags() {
		return $this->hasList($this->tags);		
	}
	
	public function hasCcList() {
		return $this->hasList($this->ccList);
	}
	
	public function hasBccList() {
		return $this->hasList($this->bccList);
	}
	
	private function hasList($list) {
		return $list && count($list) > 0;
	}
		
	/**
	 * @return the $folderId
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
	 * @return the $number
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @return the $ownerId
	 */
	public function getOwnerId() {
		return $this->ownerId;
	}

	/**
	 * @return the $mailboxId
	 */
	public function getMailboxId() {
		return $this->mailboxId;
	}

	/**
	 * @return the $customerId
	 */
	public function getCustomerId() {
		return $this->customerId;
	}

	/**
	 * @return the $threadCount
	 */
	public function getThreadCount() {
		return $this->threadCount;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return the $subject
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return the $preview
	 */
	public function getPreview() {
		return $this->preview;
	}

	/**
	 * @return the $createdBy
	 */
	public function getCreatedBy() {
		return $this->createdBy;
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
	 * @return the $closedAt
	 */
	public function getClosedAt() {
		return $this->closedAt;
	}

	/**
	 * @return the $closedBy
	 */
	public function getClosedBy() {
		return $this->closedBy;
	}

	/**
	 * @return the $source
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @return the $ccList
	 */
	public function getCcList() {
		return $this->ccList;
	}

	/**
	 * @return the $bccList
	 */
	public function getBccList() {
		return $this->bccList;
	}

	/**
	 * @return the $tags
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @return the $threads
	 */
	public function getThreads($cache=true, $apiCall=true) {
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
}