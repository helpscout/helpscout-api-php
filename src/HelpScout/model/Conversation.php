<?php
namespace HelpScout\model;

class Conversation {	
	const OWNER_ANYONE = 1;

	private $id             = false;
    private $type           = false;
	private $folderId       = 0;
	private $draft          = false;
	private $number         = 0;
	private $owner          = false;
	private $mailbox        = false;
	private $customer       = false;
	private $threadCount    = 0;
	private $status         = false;
	private $subject        = false;
	private $preview        = false;
	private $createdBy      = false;
	private $createdAt      = false;
	private $modifiedAt     = false;
	private $closedAt       = false;
	private $closedBy       = 0;
	private $source         = false;
	private $ccList         = false;
	private $bccList        = false;
	private $tags           = false;
	private $threads        = false;
	
	public function __construct($data=null) {		
		if ($data) {
			$this->id               = $data->id;
            $this->type             = $data->type;
			$this->folderId         = $data->folderId;
			$this->draft            = $data->isDraft;
			$this->number           = $data->number;
            $this->createdByType    = $data->createdByType;
			
			if (isset($data->owner)) {
				$this->owner = new \HelpScout\model\ref\PersonRef($data->owner);
			}
			
			if (isset($data->mailbox)) {
				$this->mailbox = new \HelpScout\model\ref\MailboxRef($data->mailbox);
			}
			
			if (isset($data->customer)) {
				$this->customer = new \HelpScout\model\ref\PersonRef($data->customer);
			}
			
			$this->source      = $data->source;
			$this->threadCount = $data->threadCount;
			$this->status      = $data->status;
			$this->subject     = $data->subject;
			$this->preview     = $data->preview;
            $this->createdBy = new \HelpScout\model\ref\PersonRef($data->createdBy);

			$this->createdAt   = $data->createdAt;
			$this->modifiedAt  = $data->modifiedAt;
			$this->closedAt    = $data->closedAt;
			$this->ccList      = $data->cc;
			$this->bccList     = $data->bcc;
			$this->tags        = $data->tags;
			
			if ($data->closedBy) {
				$this->closedBy = new \HelpScout\model\ref\PersonRef($data->closedBy);
			}
			
			if (isset($data->threads)) {
				$this->threads = array();
				$types = array(
					'lineitem'     => '\HelpScout\model\thread\LineItem',
					'customer'     => '\HelpScout\model\thread\Customer',
					'message'      => '\HelpScout\model\thread\Message',					
					'note'         => '\HelpScout\model\thread\Note',
					'forwardparent'=> '\HelpScout\model\thread\ForwardParent',
					'forwardchild' => '\HelpScout\model\thread\ForwardChild',
                    'chat'         => '\HelpScout\model\thread\Chat'
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
	 * @return boolean
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
	 * @return int
	 */
	public function getOwnerId() {
		return $this->ownerId;
	}

	/**
	 * @return int
	 */
	public function getMailboxId() {
		return $this->mailboxId;
	}

	/**
	 * @return int
	 */
	public function getCustomerId() {
		return $this->customerId;
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
	 * @return int
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
	public function getModifiedAt() {
		return $this->modifiedAt;
	}

	/**
	 * @return string
	 */
	public function getClosedAt() {
		return $this->closedAt;
	}

	/**
	 * @return int
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

	/**
	 * @return array
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