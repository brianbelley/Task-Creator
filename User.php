<?php
require_once 'dbConfig.php';

class User {
  protected string $userName;
  protected string $firstName;
  protected string $lastName;
  protected string $password;
  protected $db;

  public function __construct($userName, $firstName, $lastName, $password) {
    $this->db = $db;
    $this->userName = $userName;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->password = $password;
  }

  public function getUserName() {
    return $this->userName;
  }

  public function setUserName($userName) {
    $this->userName = $userName;
  }

  public function getFirstName() {
    return $this->firstName;
  }

  public function setFirstName($firstName) {
    $this->firstName = $firstName;
  }

  public function getLastName() {
    return $this->lastName;
  }

  public function setLastName($lastName) {
    $this->lastName = $lastName;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }
  
  // Properties and other methods here...

  public function __toString() {
    return "User: { userName = " . $this->userName . ", firstName = " . $this->firstName . ", lastName = " . $this->lastName . ", password = " . $this->password . " }";
  }
  
  public function addUser($userName, $firstName, $lastName, $password) {
      // Prepare a SQL statement to insert the user into the "user" table
      $stmt = $this->db->prepare("INSERT INTO user (username, firstname, lastname, password) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $userName, $firstName, $lastName, $password);
      
      // Execute the SQL statement
      if ($stmt->execute()) {
          return true; // User added successfully
      } else {
          return false; // Error adding user
      }
  }
}
?>


