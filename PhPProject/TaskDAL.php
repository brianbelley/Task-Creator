<?php
require_once('dbConfig.php');
require_once('Task.php');

class TaskDAL {
    public static function getTasksByUserId($userId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = "SELECT * FROM task WHERE userId = " . mysqli_real_escape_string($conn, $userId);
        $result = mysqli_query($conn, $stmt);
        $tasks = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $task = new Task($row['userId'], $row['description'], $row['date']);
            $task->setTaskId($row['taskId']);
            array_push($tasks, $task);
        }
        mysqli_close($conn);
        return $tasks;
    }
    
    public static function getTaskByTaskId($taskId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "SELECT task.taskId, user.userId, user.firstName, user.lastName, task.description, task.date FROM task INNER JOIN user ON task.userId = user.userId WHERE task.taskId = $taskId";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            return null;
        }
        
        $row = mysqli_fetch_assoc($result);
        
        $user = new User($row['userId'], $row['firstName'], $row['lastName']);
        $task = new Task($user, $row['description'], $row['date']);
        $task->setTaskId($row['taskId']);
        
        mysqli_free_result($result);
        mysqli_close($conn);
        
        return $task;
    }
    
     

    public static function addTask($task) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = "INSERT INTO task (taskId, userId, description, date) VALUES ('" . mysqli_real_escape_string($conn, $task->getTaskId()). "', '" . mysqli_real_escape_string($conn, $task->getUserId()) . "', '" . mysqli_real_escape_string($conn, $task->getDescription()) . "', '" . mysqli_real_escape_string($conn, $task->getDate()) . "')";
        $result = mysqli_query($conn, $stmt);
        mysqli_close($conn);
        return $result;
    }

    public static function readTask($taskId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = "SELECT * FROM task WHERE taskId = " . mysqli_real_escape_string($conn, $taskId);
        $result = mysqli_query($conn, $stmt);
        $task = null;
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $task = new Task($row['userId'], $row['description'], $row['date']);
            $task->setTaskId($row['taskId']);
        }
        mysqli_close($conn);
        return $task;
    }

    public static function updateTask($task) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = "UPDATE task SET userId = '" . mysqli_real_escape_string($conn, $task->getUserId()) . "', description = '" . mysqli_real_escape_string($conn, $task->getDescription()) . "', date = '" . mysqli_real_escape_string($conn, $task->getDate()) . "' WHERE taskId = " . mysqli_real_escape_string($conn, $task->getTaskId());
        $result = mysqli_query($conn, $stmt);
        mysqli_close($conn);
        return $result;
    }
    
    

    public static function deleteTask($taskId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = "DELETE FROM task WHERE taskId = " . mysqli_real_escape_string($conn, $taskId);
        $result = mysqli_query($conn, $stmt);
        mysqli_close($conn);
        return $result;
    }
}
