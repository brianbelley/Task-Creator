<?php
require_once 'User.php';

class Task extends User {
    protected static $counter1 = 0;
    protected int $taskId;
    protected string $description;
    protected string $date;
    
    public function __construct($userId,$description, $date) {
        $this->userId = parent::$counter;
        $this->description = $description;
        $this->date = $date;
        self::$counter1++; // increment the counter for each new object
        $this->taskId = self::$counter1; // assign the current counter value as the user ID
    }
    
    public function getTaskId() {
        return $this->taskId;
    }
    
    public function setTaskId($taskId) {
        $this->taskId = $taskId;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function setDate($date) {
        $this->date = $date;
    }
}

