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
        $_SESSION['userId'] = $user->getUserId();
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
    
    // Automatically log in the new user
    $_SESSION['userId'] = $user->getUserId();
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login/Register</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) { ?>
        <p><?php echo $error ?></p>
    <?php } ?>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login">
    </form>
    
    <h1>Register</h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" required>
        <br>
        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>
