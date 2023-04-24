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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100">
<div class="max-w-2xl mx-auto my-12 shadow-xl">
    <div class="text-center font-bold text-4xl mb-0 bg-gray-700 rounded-tl-xl rounded-tr-xl text-white py-6">
        <p>Schedule<span class="text-indigo-400 font-mono">IT</span></p>
    </div>

    <!--New Task-->
    <div class="bg-gray-100 p-12">
        <h1 class="font-black text-2xl border-b-4 border-indigo-600 mb-7 text-indigo-600">Create Task</h1>
        <form method="POST" class="space-y-6 mb-8">
            <div class="flex flex-col space-y-2">
                <label class="text-md font-medium">Description:</label>
                <textarea name="description" required class="p-4 rounded-xl border border-gray-300 shadow-inner"></textarea>
            </div>
            <div class="flex flex-col space-y-2">
                <label class="text-md font-medium">Date:</label>
                <input type="date" name="date" required class="p-4 rounded-xl border border-gray-300 shadow-inner">
            </div>
            <div class="flex justify-between">
                <input type="submit" name="submit" value="Create" class="bg-indigo-600 text-white px-3 py-2 rounded-sm hover:bg-indigo-800 cursor-pointer">
                <a href="dashboard.php" class="bg-indigo-300 px-3 py-2 rounded-sm hover:bg-indigo-400">
                    Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
