<?php
require_once 'Task.php';
require_once 'TaskDAL.php';
require_once 'UserDAL.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $taskId = $_POST['taskId'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    
    $task = TaskDAL::getTaskByTaskId($taskId);
    $task->setDescription($description);
    $task->setDate($date);
    
    TaskDAL::updateTask($task);
    
    header('Location: dashboard.php');
    exit;
}

if (isset($_GET['taskId'])) {
    $taskId = $_GET['taskId'];
    $task = TaskDAL::getTaskByTaskId($taskId);
} else {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Task</title>
</head>
<body>
    <h1>Update Task</h1>
    <form method="POST">
        <input type="hidden" name="taskId" value="<?php echo $task->getTaskId(); ?>">
        <label>Description:</label>
        <input type="text" name="description" value="<?php echo $task->getDescription(); ?>" required>
        <br>
        <label>Date:</label>
        <input type="date" name="date" value="<?php echo $task->getDate(); ?>" required>
        <br>
        <input type="submit" name="submit" value="Update">
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
