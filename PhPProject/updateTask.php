<?php
require_once 'TaskDAL.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['taskId']) && isset($_POST['description']) && isset($_POST['date'])) {
    $taskId = $_POST['taskId'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $user = new User($_SESSION['userId'], $_SESSION['firstName'], $_SESSION['lastName'], $_SESSION['password']);
    TaskDAL::updateTask($taskId, $description, $date, $user);
    header('Location: dashboard.php');
    exit;
}

$taskId = $_GET['id'];
$task = TaskDAL::getTaskByTaskId($taskId);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Task</title>
</head>
<?php include('header.php'); ?>
<body>
    <h1>Update Task</h1>
    <form method="POST">
        <input type="hidden" name="taskId" value="<?php echo $taskId; ?>">
        <div>
            <label for="description">Description:</label>
            <input type="text" name="description" value="<?php echo $task->getDescription(); ?>">
        </div>
        <div>
            <label for="date">Date:</label>
            <input type="date" name="date" value="<?php echo $task->getDate(); ?>">
        </div>
        <button type="submit" name="updateTask">Update Task</button>
    </form>
    <div>
        <a href="dashboard.php"><button>Back to Dashboard</button></a>
    </div>
</body>
<?php include('footer.php'); ?>
</html> 