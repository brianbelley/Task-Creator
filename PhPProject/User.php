<?php
class User {
    protected static $counter = 0;
    protected $userId;
    protected $userName;
    protected $firstName;
    protected $lastName;
    protected $password;

    public function __construct($userName, $firstName, $lastName, $password) {
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        self::$counter++; // increment the counter for each new object
        $this->userId = self::$counter; // assign the current counter value as the user ID
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserName($newName) {
        $this->userName = $newName;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setFirstName($newName) {
        $this->firstName = $newName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setLastName($newName) {
        $this->lastName = $newName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setPassword($newPassword) {
        $this->password = $newPassword;
    }

    public function getPassword() {
        return $this->password;
    }
}
