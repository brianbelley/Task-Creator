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

// get user's tasks
$tasks = TaskDAL::getTasksByUserId($_SESSION['userId']);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <h1>Dashboard</h1>

  <?php if (count($tasks) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Task ID</th>
          <th>Description</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tasks as $task): ?>
          <tr>
            <td><?php echo $task->getTaskId(); ?></td>
            <td><?php echo $task->getDescription(); ?></td>
            <td><?php echo $task->getDate(); ?></td>
            <td>
              <form action="updateTask.php" method="POST">
                <input type="hidden" name="taskId" value="<?php echo $task->getTaskId(); ?>">
                <input type="submit" value="Update">
              </form>
              <form action="deleteTask.php" method="POST">
                <input type="hidden" name="taskId" value="<?php echo $task->getTaskId(); ?>">
                <input type="submit" value="Delete">
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>You created no task yet</p>
  <?php endif; ?>

  <form action="createTask.php" method="POST">
    <input type="submit" value="Create Task">
  </form>

  <form action="logout.php" method="POST">
    <input type="submit" value="Logout">
  </form>

</body>
</html>
