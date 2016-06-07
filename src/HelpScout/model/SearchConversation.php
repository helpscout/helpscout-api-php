<?php
namespace HelpScout\model;

class SearchConversation {

    private $id = null;
    private $number = null;
    private $mailboxId = null;
    private $status = null;
    private $subject = null;
    private $threadCount = null;
    private $preview = null;
    private $customerName = null;
    private $customerEmail = null;
    private $modifiedAt = null;

    public function __construct($data=null) {
        if ($data) {
            $this->id = isset($data->id) ? $data->id : null;
            $this->number = isset($data->number) ? $data->number : null;
            $this->mailboxId = isset($data->mailboxId) ? $data->mailboxId : null;
            $this->status = isset($data->status) ? $data->status : null;
            $this->subject = isset($data->subject) ? $data->subject : null;
            $this->threadCount = isset($data->threadCount) ? $data->threadCount : null;
            $this->preview = isset($data->preview) ? $data->preview : null;
            $this->customerName = isset($data->customerName) ? $data->customerName : null;
            $this->customerEmail = isset($data->customerEmail) ? $data->customerEmail : null;
            $this->modifiedAt = isset($data->modifiedAt) ? $data->modifiedAt : null;
        }
    }

    /**
     * @return array
     */
    public function getObjectVars() {
        return get_object_vars($this);
    }

    /**
     * @param null $customerEmail
     */
    public function setCustomerEmail($customerEmail) {
        $this->customerEmail = $customerEmail;
    }

    /**
     * @return null
     */
    public function getCustomerEmail() {
        return $this->customerEmail;
    }

    /**
     * @param null $customerName
     */
    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }

    /**
     * @return null
     */
    public function getCustomerName() {
        return $this->customerName;
    }

    /**
     * @param null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param null $mailboxId
     */
    public function setMailboxId($mailboxId) {
        $this->mailboxId = $mailboxId;
    }

    /**
     * @return null
     */
    public function getMailboxId() {
        return $this->mailboxId;
    }

    /**
     * @param null status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param null $modifiedAt
     */
    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return null
     */
    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    /**
     * @param null $number
     */
    public function setNumber($number) {
        $this->number = $number;
    }

    /**
     * @return null
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * @param null $preview
     */
    public function setPreview($preview) {
        $this->preview = $preview;
    }

    /**
     * @return null
     */
    public function getPreview() {
        return $this->preview;
    }

    /**
     * @param null $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @return null
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param null $threadCount
     */
    public function setThreadCount($threadCount) {
        $this->threadCount = $threadCount;
    }

    /**
     * @return null
     */
    public function getThreadCount() {
        return $this->threadCount;
    }


} 
