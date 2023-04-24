<?php
require_once 'TaskDAL.php';
require_once 'UserDAL.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}

$tasks = TaskDAL::getTasksByUserId($_SESSION['userId']);
$user = UserDAL::readUser($_SESSION['userId']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<?php include('header.php'); ?>
<body>
    <h1>Welcome, <?php echo $user->getFirstname(); ?>!</h1>
    <h2>Dashboard</h2>
    <?php if (!empty($tasks)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) { ?>
    			<tr>
       				 <td><?php echo $task['description']; ?></td>
        			 <td><?php echo $task['date']; ?></td>
        			 <td>
            			<a href="updateTask.php?id=<?php echo $task['taskId']; ?>"><button>Update</button></a>
           				<a href="deleteTask.php?id=<?php echo $task['taskId']; ?>"><button>Delete</button></a>
       				 </td>
    			</tr>
			<?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>There is no task available.</p>
    <?php } ?>
    <div>
        <a href="createTask.php"><button>Create Task</button></a>
        <a href="logout.php"><button>Logout</button></a>
    </div>
</body>
<?php include('footer.php'); ?>
</html>
