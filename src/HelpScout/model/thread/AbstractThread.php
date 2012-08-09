<?php
namespace HelpScout\model\thread;

interface ConversationThread {
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
}

abstract class AbstractThread extends LineItem implements ConversationThread {
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
			
			if ($data->customer) {				
				$this->customer = new \HelpScout\model\ref\CustomerRef($data->customer);
			}	

			if ($data->attachments) {
				$this->attachments = array();
				foreach($data->attachments as $at) {
					$this->attachments[] = new \HelpScout\model\Attachment($at);
				}				
			}			
		}
	}
	
	public function isPublished() {
		return $this->state == 'published';
	}
	
	public function isDraft() {
		return $this->state == 'draft';		
	}
	
	public function isHeldForReview() {
		return $this->state == 'underreview';
	}
	
	public function hasAttachments() {
		return $this->attachments && count($this->attachments) > 0;
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
}
