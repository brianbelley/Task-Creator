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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // get task ID from form
  $taskId = $_POST['taskId'];

  // get task details by ID
  $task = TaskDAL::getTaskByTaskId($taskId);

  if (!$task) {
    // task not found, redirect to dashboard
    header('Location: dashboard.php');
    exit();
  }
} else {
  // invalid request method, redirect to dashboard
  header('Location: dashboard.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // update task details
  $description = $_POST['description'];
  $date = $_POST['date'];

  $task->setDescription($description);
  $task->setDate($date);

  // update task in database
  TaskDAL::updateTask($task);

  // redirect to dashboard
  header('Location: dashboard.php');
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Task</title>
</head>
<body>
  <h1>Update Task</h1>

  <form action="updateTask.php" method="POST">
    <label for="description">Description:</label>
    <input type="text" id="description" name="description" value="<?php echo $task->getDescription(); ?>" required>
    <br>
    <label for="date">Date:</label>
    <input type="date" id="date" name="date" value="<?php echo $task->getDate(); ?>" required>
    <br>
    <input type="hidden" name="taskId" value="<?php echo $task->getTaskId(); ?>">
    <input type="submit" value="Update Task">
  </form>

  <form action="dashboard.php" method="POST">
    <input type="submit" value="Cancel">
  </form>

</body>
</html>
