<?php
require_once('dbConfig.php');
require_once('User.php');

class UserDAL {
    public static function getUserByUsernameAndPassword($username, $password) {
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Prepare the SQL statement
        $stmt = mysqli_prepare($db, "SELECT userId, firstName, lastName FROM user WHERE userName = ? AND password = ?");
        
        // Bind the parameters
        $stmt->bind_param("ss", $username, $password);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result to variables
        $stmt->bind_result($userId, $firstName, $lastName);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement and connection
        $stmt->close();
        $db->close();
        
        // If a user was found, return an associative array with user details
        if ($userId) {
            return array(
                "userId" => $userId,
                "username" => $username,
                "firstName" => $firstName,
                "lastName" => $lastName
            );
        } else {
            return false;
        }
    }
    
    
    
    public static function readUser($userId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $stmt = "SELECT * FROM user WHERE userId = " . mysqli_real_escape_string($conn, $userId);
        $result = mysqli_query($conn, $stmt);
        $user = null;
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $user = new User($row['userId'], $row['firstName'], $row['lastName'], $row['password']);
        }
        mysqli_close($conn);
        return $user;
    }
    
    
    
    public static function addUser($user) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $userid = self::incrementUserId(); // get the next user ID
        $username = $user->getUsername();
        $firstname = $user->getFirstName();
        $lastname = $user->getLastName();
        $password = $user->getPassword();
        
        $stmt = mysqli_prepare($conn, "INSERT INTO user (userId,userName,firstName,lastName, password) VALUES (?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "sssss",$userid, $username,$firstname,$lastname, $password);
        $result = mysqli_stmt_execute($stmt);
        
        // Create a new User object with the same userId as in the database
        $newUser = new User($userid, $username, $firstname, $lastname, $password);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $newUser;
    }
    
    
    private static function incrementUserId() {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $stmt = mysqli_prepare($conn, "SELECT MAX(userId) FROM user");
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $maxUserId);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return ++$maxUserId; // increment the maximum user ID by 1 and return it
    }
    
    
    public static function getUserByUsername($username) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $user = null;
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user = new User($row['username'], $row['password'], $row['email']);
            $user->setId($row['id']);
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $user;
    }
    
    public static function getUserById($userId) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $stmt = $conn->prepare('SELECT * FROM user WHERE userId = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User($row['userName'], $row['firstName'],$row['lastName'], $row['password']);
            return $user;
        } else {
            return false;
        }
    }
    
    
    public static function updateUser($user) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $stmt = mysqli_prepare($conn, "UPDATE user SET username = ?, password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $user->getUsername(), $user->getPassword(), $user->getId());
        $result = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $result;
    }
    
    public static function deleteUser($id) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $stmt = mysqli_prepare($conn, "DELETE FROM user WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $result = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $result;
    }
    
    public static function getAllUsers() {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $stmt = mysqli_prepare($conn, "SELECT * FROM user");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $user = new User($row['username'], $row['password']);
            $user->setId($row['id']);
            array_push($users, $user);
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $users;
    }
}



