<?php
namespace HelpScout\model;

class Workflow {
	private $id = false;
	private $mailboxId;
	private $type;
	private $status;
	private $order;
	private $name;
	private $createdAt;
	private $modifiedAt;

	public function __construct($data=null) {
		if ($data) {
			$this->id        = isset($data->id)         ? $data->id        : null;
			$this->mailboxId = isset($data->mailboxId)  ? $data->mailboxId : null;
			$this->type      = isset($data->type)       ? $data->type      : null;
			$this->status    = isset($data->status)     ? $data->status    : null;
			$this->order     = isset($data->order)      ? $data->order     : null;
			$this->name      = isset($data->name)       ? $data->name      : null;
			$this->createdAt = isset($data->createdAt)  ? $data->createdAt : null;
			$this->modifiedAt= isset($data->modifiedAt) ? $data->modifiedAt: null;
		}
	}

	public function toJSON() {
		return json_encode(get_object_vars($this));
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setMailboxId($mailboxId) {
		$this->mailboxId = $mailboxId;
	}

	public function getMailboxId() {
		return $this->mailboxId;
	}

	public function setModifiedAt($modifiedAt) {
		$this->modifiedAt = $modifiedAt;
	}

	public function getModifiedAt() {
		return $this->modifiedAt;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}
}
