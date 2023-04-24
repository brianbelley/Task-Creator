<?php
require_once 'dbConfig.php';
require_once 'UserDAL.php';

session_start();

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['userId'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the user exists in the database
    $user = UserDAL::getUserByUsernameAndPassword($username, $password);
    
    if ($user) {
        $_SESSION['userId'] = $user['userId'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password";
    }
}

// Handle register form submission
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    
    // Create a new user in the database
    $user = new User($username, $firstName, $lastName, $password);
    UserDAL::addUser($user);
    
    // Reload the index
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login/Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100">
    <div class="max-w-2xl mx-auto my-12 shadow-xl">
        <div class="text-center font-bold text-4xl mb-0 bg-gray-700 rounded-tl-xl rounded-tr-xl text-white py-6">
            <p>Schedule<span class="text-indigo-400 font-mono">IT</span></p>
        </div>

        <!--Login form-->
        <div class="bg-gray-100 p-12">
            <h1 class="font-black text-2xl border-b-4 border-indigo-600 mb-7 text-indigo-600">Login</h1>
            <?php if (isset($error)) { ?>
                <p><?php echo $error ?></p>
            <?php } ?>
            <form method="post" class="mb-12 space-y-4">
                <div class="flex flex-col space-y-2">
                    <label class="text-md font-medium">Username:</label>
                    <input type="text" name="username" class="p-2 rounded-lg border border-gray-300 shadow-inner">
                </div>
                <div class="flex flex-col space-y-2">
                    <label class="text-md font-medium">Password:</label>
                    <input type="password" name="password" class="p-2 rounded-lg border border-gray-300 shadow-inner">
                </div>
                <input type="submit" name="login" value="Login" class="bg-indigo-600 text-white px-3 py-2 rounded-sm hover:bg-indigo-800 cursor-pointer">
            </form>

            <!--Register form-->
            <h1 class="font-black text-2xl border-b-4 border-purple-600 mb-7 text-purple-600">Register</h1>
            <form method="post" class="space-y-4">
                <div class="flex flex-col space-y-2">
                    <label class="text-md font-medium" for="username">Username:</label>
                    <input type="text" name="username" class="p-2 rounded-lg border border-gray-300 shadow-inner" required>
                </div>
                <div class="flex flex-col space-y-2">
                    <label class="text-md font-medium" for="firstName">First Name:</label>
                    <input type="text" name="firstName" class="p-2 rounded-lg border border-gray-300 shadow-inner" required>
                </div>
                <div class="flex flex-col space-y-2">
                    <label class="text-md font-medium" for="lastName">Last Name:</label>
                    <input type="text" name="lastName" class="p-2 rounded-lg border border-gray-300 shadow-inner" required>
                </div>
                <div class="flex flex-col space-y-2">
                    <label class="text-md font-medium" for="password">Password:</label>
                    <input type="password" name="password" class="p-2 rounded-lg border border-gray-300 shadow-inner" required>
                </div>
                <input type="submit" name="register" value="Register" class="bg-purple-600 text-white px-3 py-2 rounded-sm hover:bg-purple-800 cursor-pointer">
            </form>
        </div>
    </div>
</body>
</html>
