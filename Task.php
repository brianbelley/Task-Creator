<?php
require_once('dbConfig.php');
require_once('User.php');

class Task extends User {
    protected int $taskId;
    protected string $userName;
    protected string $description;
    protected string $date;
    
    public function __construct($userName, $description, $date) {
        parent::__construct($userName);
        $this->description = $description;
        $this->date = $date;
    }
    
    public function getTaskId() {
        return $this->taskId;
    }
    
    public function setTaskId($taskId) {
        $this->taskId = $taskId;
    }
    
    public function getUserName() {
        return $this->userName;
    }
    
    public function setUserName($userName) {
        $this->userName = $userName;
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
    
    public function createTask() {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $userName = $this->getUserName();
        $description = $this->getDescription();
        $date = $this->getDate();
        
        $sql = "INSERT INTO tasks (user_name, description, date) VALUES ('$userName', '$description', '$date')";
        
        if (mysqli_query($conn, $sql)) {
            $this->setTaskId(mysqli_insert_id($conn));
            echo "Task created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        mysqli_close($conn);
    }
    
    public function readTask($taskId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $sql = "SELECT * FROM tasks WHERE task_id=$taskId";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $this->setTaskId($row['task_id']);
            $this->setUserName($row['user_name']);
            $this->setDescription($row['description']);
            $this->setDate($row['date']);
            echo "Task retrieved successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        mysqli_close($conn);
    }
    
    public function updateTask() {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $taskId = $this->getTaskId();
        $description = $this->getDescription();
        $date = $this->getDate();
        
        $sql = "UPDATE tasks SET description='$description', date='$date' WHERE task_id=$taskId";
        
        if (mysqli_query($conn, $sql)) {
            echo "Task updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $conn->close();
    }
}
 ?>
        
