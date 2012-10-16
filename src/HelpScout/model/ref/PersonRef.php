<?php
namespace HelpScout\model\ref;

class PersonRef {
    private $id = false;
    private $firstName;
    private $lastName;
    private $email;
    private $type;

    public function __construct($data=null) {
        if ($data) {
            $this->id         = $data->id;
            $this->firstName  = $data->firstName;
            $this->lastName   = $data->lastName;
            $this->email      = $data->email;
            $this->type       = $data->type;
        }
    }

    public function getObjectVars() {
        return get_object_vars($this);
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getId() {
        return $this->id;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getType() {
        return $this->type;
    }
}