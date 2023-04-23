<?php
$host = "127.0.0.1"; // Replace with your host name
$user = "root"; // Replace with your database username
$pass = ""; // Replace with your database password
$dbname = "calendar"; // Replace with your database name

// Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>
