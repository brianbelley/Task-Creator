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
    $userId = $_SESSION['userId'];
    $description = $_POST['description'];
    $date = date('Y-m-d H:i:s');
    $user = UserDAL::getUserById($userId);
    $task = new Task($user, $description, $date);
    TaskDAL::addTask($task);
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
</head>
<body>
    <h1>Create Task</h1>
    <form method="POST">
        <label>Description:</label>
        <input type="text" name="description" required>
        <br>
        <input type="submit" name="submit" value="Create">
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

