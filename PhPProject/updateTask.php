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
<body class="bg-indigo-100">
    <div class="max-w-2xl mx-auto my-12 shadow-xl">
        <div class="text-center font-bold text-4xl mb-0 bg-gray-700 rounded-tl-xl rounded-tr-xl text-white py-6">
            <p>Schedule<span class="text-indigo-400 font-mono">IT</span></p>
        </div>

        <!--Update Task-->
        <div class="bg-gray-100 p-12">
            <h1 class="font-black text-2xl border-b-4 border-indigo-600 mb-7 text-indigo-600">Update Task</h1>
            <form method="POST" class="space-y-6 mb-8">
                <input type="hidden" name="taskId" value="<?php echo $taskId; ?>">
                <div class="flex flex-col space-y-2">
                    <label for="description" class="text-md font-medium">Description:</label>
                    <input type="text" name="description" value="<?php echo $task->getDescription(); ?>" class="p-4 rounded-xl border border-gray-300 shadow-inner">
                </div>
                <div class="flex flex-col space-y-2">
                    <label for="date" class="text-md font-medium">Date:</label>
                    <input type="date" name="date" value="<?php echo $task->getDate(); ?>" class="p-4 rounded-xl border border-gray-300 shadow-inner">
                </div>
                <div class="flex justify-between">
                    <button type="submit" name="updateTask" class="bg-indigo-600 text-white px-3 py-2 rounded-sm hover:bg-indigo-800 cursor-pointer mr-2">
                        Update Task
                    </button>
                    <a href="dashboard.php" class="px-3 py-2 rounded-sm hover:bg-indigo-600 hover:text-white text-black border-4 border-indigo-600">
                        <button>Back to Dashboard</button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 