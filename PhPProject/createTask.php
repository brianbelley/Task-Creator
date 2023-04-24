<?php
require_once 'Task.php';
require_once 'TaskDAL.php';
require_once 'UserDAL.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['userId'];
$user = UserDAL::getUserById($userId);
$firstName = $user->getFirstName();

if (isset($_POST['submit'])) {
    $description = $_POST['description'];
    $date = $_POST['date'];
    $task = new Task($userId, $description, $date);
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
        <label>Date:</label>
        <input type="date" name="date" required>
        <br>
        <input type="submit" name="submit" value="Create">
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
