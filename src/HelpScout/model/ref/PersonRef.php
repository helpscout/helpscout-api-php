<?php
namespace HelpScout\model\ref;

class PersonRef {
    private $id = false;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $type;

    public function __construct($data=null) {
        if ($data) {
            $this->id         = $data->id;
            $this->firstName  = $data->firstName;
            $this->lastName   = $data->lastName;
            $this->email      = $data->email;
            $this->phone      = $data->phone;
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
    	$type = strtolower(trim($type));
    	if (!in_array($type, array('customer','user'))) {
    		throw new \Exception('Invalid type set on PersonRef [' . $type . ']. Must be one of type customer or user.');
    	}
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

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getPhone() {
        return $this->phone;
    }
}