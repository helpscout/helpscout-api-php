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
	public function getCreatedBy();
	public function getFromMailbox();
    public function getObjectVars();

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
}

abstract class AbstractThread extends LineItem implements ConversationThread {
    private $type;
	private $state;
	private $body;
	private $toList;
	private $ccList;
	private $bccList;
	private $customer;
	
	private $attachments;
	
	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {
			$this->body        = $data->body;
			$this->toList      = $data->to;			
			$this->ccList      = $data->cc;			
			$this->bccList     = $data->bcc;
			$this->state       = $data->state;
            $this->type        = $data->type;
			
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
        $vars = get_object_vars($this);
        if ($this->getAssignedTo() != null) {
            $vars['assignedTo'] = $this->getAssignedTo()->getObjectVars();
        }

        if ($this->getCreatedBy() != null) {
            $vars['createdBy'] = $this->getCreatedBy()->getObjectVars();
        }

        if ($this->getFromMailbox() != null) {
            $vars['fromMailbox'] = $this->getFromMailbox()->getObjectVars();
        }

        if ($this->getCustomer() != null) {
            $vars['customer'] = $this->getCustomer()->getObjectVars();
        }
        return $vars;
    }

    /**
     * @param $attachments
     */
    public function setAttachments($attachments) {
        $this->attachments = $attachments;
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
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

	/**
	 * @return the $state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return the $body
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @return the $toList
	 */
	public function getToList() {
		return $this->toList;
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
	 * @return the $attachments
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
