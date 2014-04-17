<?php
namespace HelpScout\model;

class SearchCustomer {

    private $id;
    private $firstName;
    private $lastName;
    private $fullName;
    private $emails;
    private $photoUrl;
    private $photoType;
    private $gender;
    private $age;
    private $organization;
    private $jobTitle;
    private $location;
    private $createdAt;
    private $modifiedAt;

    public function __construct($data=null) {
        if ($data) {
            $this->id = isset($data->id) ? $data->id : null;
            $this->firstName = isset($data->firstName) ? $data->firstName : null;
            $this->lastName = isset($data->lastName) ? $data->lastName : null;
            $this->fullName = isset($data->fullName) ? $data->fullName : null;
            $this->emails = isset($data->emails) ? $data->emails : null;
            $this->photoUrl = isset($data->photoUrl) ? $data->photoUrl : null;
            $this->photoType = isset($data->photoType) ? $data->photoType : null;
            $this->gender = isset($data->gender) ? $data->gender : null;
            $this->age = isset($data->age) ? $data->age : null;
            $this->organization = isset($data->organization) ? $data->organization : null;
            $this->jobTitle = isset($data->jobTitle) ? $data->jobTitle : null;
            $this->location = isset($data->location) ? $data->location : null;
            $this->createdAt = isset($data->createdAt) ? $data->createdAt : null;
            $this->modifiedAt = isset($data->modifiedAt) ? $data->modifiedAt : null;
        }
    }

    /**
     * @param mixed $age
     */
    public function setAge($age) {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getAge() {
        return $this->age;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param mixed $emails
     */
    public function setEmails($emails) {
        $this->emails = $emails;
    }

    /**
     * @return mixed
     */
    public function getEmails() {
        return $this->emails;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $jobTitle
     */
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return mixed
     */
    public function getJobTitle() {
        return $this->jobTitle;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param mixed $modifiedAt
     */
    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization) {
        $this->organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * @param mixed $photoType
     */
    public function setPhotoType($photoType) {
        $this->photoType = $photoType;
    }

    /**
     * @return mixed
     */
    public function getPhotoType() {
        return $this->photoType;
    }

    /**
     * @param mixed $photoUrl
     */
    public function setPhotoUrl($photoUrl) {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return mixed
     */
    public function getPhotoUrl() {
        return $this->photoUrl;
    }


} 