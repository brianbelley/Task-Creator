<?php
require_once 'TaskDAL.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];
    TaskDAL::deleteTask($taskId);
}

header('Location: dashboard.php');
exit;
?>
