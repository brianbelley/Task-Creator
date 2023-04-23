<?php
require_once('dbConfig.php');
require_once('Task.php');
require_once('TaskDAL.php');

// start session
session_start();

// check if user is logged in
if (!isset($_SESSION['userId'])) {
  // redirect to login page
  header('Location: index.php');
  exit();
}

// check if taskId is set
if (!isset($_POST['taskId'])) {
  // redirect to dashboard
  header('Location: dashboard.php');
  exit();
}

$taskId = $_POST['taskId'];

// delete task from database
TaskDAL::deleteTask($taskId);

// redirect to dashboard
header('Location: dashboard.php');
exit();
?>