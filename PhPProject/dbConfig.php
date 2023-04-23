<?php

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'calendar');

class dbConfig {
    private static $conn = null;
    
    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!self::$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        }
        return self::$conn;
    }
}

require_once('TaskDAL.php');
require_once('UserDAL.php');
