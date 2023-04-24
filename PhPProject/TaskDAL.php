<?php
require_once('dbConfig.php');
require_once('Task.php');

class TaskDAL {
    public static function getTasksByUserId($userId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $stmt = mysqli_prepare($conn, "SELECT * FROM task WHERE userId = ? ORDER BY date DESC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tasks = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $task = array(
                'taskId' => $row['taskId'],
                'description' => $row['description'],
                'date' => $row['date']
            );
            $tasks[] = $task;
        }
        mysqli_stmt_close($stmt);
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
        
        $user = new User($row['userId'], $row['firstName'], $row['lastName'],$row['password']);
        $task = new Task($user, $row['description'], $row['date']);
        $task->setTaskId($row['taskId']);
        
        mysqli_free_result($result);
        mysqli_close($conn);
        
        return $task;
    }
    
     

    public static function addTask($task) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $userId = $task->getUserId();
        $description = $task->getDescription();
        $date = $task->getDate();
        
        // Increment the taskId for each new task
        $taskId = self::getNextTaskId();
        
        $stmt = mysqli_prepare($conn, "INSERT INTO task (taskId, userId, description, date) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iiss", $taskId, $userId, $description, $date);
        $result = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $result;
    }
    
    private static function getNextTaskId() {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "SELECT MAX(taskId) FROM task";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $taskId = $row['MAX(taskId)'] + 1;
        } else {
            $taskId = 1;
        }
        
        mysqli_free_result($result);
        mysqli_close($conn);
        
        return $taskId;
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

    public static function updateTask($taskId, $description, $date, User $user)
    {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "UPDATE task SET description = ?, date = ? WHERE taskId = ? AND userId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssii", $description, $date, $taskId, $user->getUserId());
        $stmt->execute();
        $stmt->close();
        $conn->close();
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
