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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php include('header.php'); ?>
<body class="bg-indigo-100">
    <div class="max-w-2xl mx-auto my-12 shadow-xl">
        <div class="text-center font-bold text-4xl mb-0 bg-gray-700 rounded-tl-xl rounded-tr-xl text-white py-6">
            <p>Schedule<span class="text-indigo-400 font-mono">IT</span></p>
        </div>

        <!--Tasks-->
        <div class="bg-gray-100 p-12">
            <h1 class="font-black text-2xl border-b-4 border-indigo-600 mb-7 text-indigo-600">
                Welcome, <?php echo $user->getFirstname(); ?>!
            </h1>
            <h2 class="font-bold text-xl mb-7 text-indigo-600">
                Dashboard
            </h2>
            <?php if (!empty($tasks)) { ?>
                <table class="table-auto w-full mb-6 bg-white shadow-inner">
                    <thead>
                        <tr class="border border-gray-300">
                            <th class="p-4">Description</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task) { ?>
                        <tr class="border border-gray-300">
                             <td class="px-6 py-5"><?php echo $task['description']; ?></td>
                             <td class="px-6 py-5"><?php echo $task['date']; ?></td>
                             <td class="px-6 py-5">
                                <a href="updateTask.php?id=<?php echo $task['taskId']; ?>" class="text-white text-4xl cursor-pointer mr-4">
                                    <button>📝</button>
                                </a>
                                <a href="deleteTask.php?id=<?php echo $task['taskId']; ?>" class="text-white text-4xl cursor-pointer">
                                    <button>🗑️</button>
                                </a>
                             </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>There is no task available.</p>
            <?php } ?>
            <div class="flex justify-between">
                <a href="createTask.php" class="bg-indigo-600 text-white px-3 py-2 rounded-sm hover:bg-indigo-800 cursor-pointer mr-2">
                    <button>Create Task</button>
                </a>
                <a href="logout.php" class="px-3 py-2 rounded-sm hover:bg-indigo-600 hover:text-white text-black border-4 border-indigo-600">
                    <button>Logout</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
