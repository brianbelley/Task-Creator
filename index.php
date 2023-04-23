<!DOCTYPE html>
<html>
<head>
	<title>Task Manager</title>
</head>
<body>
	<h1>Task Manager</h1>

	<?php
	require_once('User.php');
	require_once('Task.php');

	 //Initialize session
	session_start();

	// Check if user is logged in, if not redirect to login page
	if(!isset($_SESSION["user"])){
		header("location: login.php");
		exit;
	}

	// Create new task
	if(isset($_POST['submitTask'])){
		$userName = $_SESSION["user"]->getUserName();
		$description = $_POST['description'];
		$date = $_POST['date'];
		$task = new Task($userName, $description, $date);
		$task->addTask();
	}

	// Delete task
	if(isset($_GET['deleteTask'])){
		$taskId = $_GET['deleteTask'];
		$task = new Task($_SESSION["user"]->getUserName(), "", "");
		$task->deleteTask($taskId);
	}

	// Update task
	if(isset($_POST['updateTask'])){
		$taskId = $_POST['taskId'];
		$description = $_POST['description'];
		$date = $_POST['date'];
		$task = new Task($_SESSION["user"]->getUserName(), $description, $date);
		$task->updateTask($taskId);
	}

	?>

	<h2>Welcome, <?php echo $_SESSION["user"]->getFirstName() . " " . $_SESSION["user"]->getLastName() ?>!</h2>

	<form action="" method="post">
		<label for="description">Task Description:</label>
		<input type="text" name="description" id="description" required><br><br>
		<label for="date">Due Date:</label>
		<input type="date" name="date" id="date" required><br><br>
		<input type="submit" name="submitTask" value="Add Task">
	</form>

	<h3>Tasks:</h3>

	<table>
		<tr>
			<th>Description</th>
			<th>Date</th>
			<th>Actions</th>
		</tr>

		<?php
		// Display user's tasks
		$tasks = $_SESSION["user"]->getTasks();
		foreach($tasks as $task){
			echo "<tr>";
			echo "<td>" . $task->getDescription() . "</td>";
			echo "<td>" . $task->getDate() . "</td>";
			echo "<td><a href='edit_task.php?taskId=" . $task->getTaskId() . "'>Edit</a> | <a href='index.php?deleteTask=" . $task->getTaskId() . "'>Delete</a></td>";
			echo "</tr>";
		}
		?>

	</table>

	<a href="logout.php">Logout</a>

</body>
</html>
