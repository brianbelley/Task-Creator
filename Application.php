<!DOCTYPE html>
<html>
<head>
	<title>Calendar</title>
	<style>
		table {
			border-collapse: collapse;
		}
		td {
			padding: 10px;
			border: 1px solid black;
			text-align: center;
			width: 100px;
			height: 100px;
		}
		.today {
			background-color: yellow;
		}
	</style>
</head>
<body>

<?php
	// Set the timezone
	date_default_timezone_set('UTC');

	// Connect to the database
	$host="127.0.0.1";
	$user="root";
	$pass="";
	$dbname = "calendar";
	$conn = mysqli_connect($host,$user,$pass, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Check if the users table exists, create it if it doesn't
	$sql = "CREATE TABLE IF NOT EXISTS users (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(30) NOT NULL,
		email VARCHAR(50),
		password VARCHAR(50)
	)";
	if (!$conn->query($sql)) {
		echo "Error creating table: " . $conn->error;
	}

	// Check if the calendars table exists, create it if it doesn't
	$sql = "CREATE TABLE IF NOT EXISTS calendars (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		month INT(2) NOT NULL,
		year INT(4) NOT NULL
	)";
	if (!$conn->query($sql)) {
		echo "Error creating table: " . $conn->error;
	}

	// Check if the userCalendars table exists, create it if it doesn't
	$sql = "CREATE TABLE IF NOT EXISTS userCalendars (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		user_id INT(6) UNSIGNED NOT NULL,
		calendar_id INT(6) UNSIGNED NOT NULL,
		FOREIGN KEY (user_id) REFERENCES users(id),
		FOREIGN KEY (calendar_id) REFERENCES calendars(id)
	)";
	if (!$conn->query($sql)) {
		echo "Error creating table: " . $conn->error;
	}

	// Get the current user ID from the session or redirect to the login page
	session_start();
	if (isset($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
	} else {
		header("Location: login.php");
		exit();
	}

	// Get the current year and month from the URL or use the current month
	if (isset($_GET['month']) && isset($_GET['year'])) {
		$month = $_GET['month'];
		$year = $_GET['year'];
	} else {
		$month = date('m');
		$year = date('Y');
	}

	// Get the calendars that the user has access to
	$sql = "SELECT c.id, c.month, c.year FROM userCalendars uc JOIN calendars c ON uc.calendar_id = c.id WHERE uc.user_id = $user_id AND c.month = $month AND c.year = $year";
	$result = $conn->query($sql);
	$calendars = array();
	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {
	        $calendars[] = $row;
	    }
	} else {
	    // If the user doesn't have access to any calendars for the specified month and year, redirect to the dashboard
	    header("Location: dashboard.php");
	    exit();
	}
	
	// Get the first day of the month and the number of days in the month
	$first_day = mktime(0, 0, 0, $month, 1, $year);
	$num_days = date('t', $first_day);
	
	// Get the name of the month and year
	$month_name = date('F', $first_day);
	$year_name = date('Y', $first_day);
	
	// Create the table header with the month and year
	echo "<h1>$month_name $year_name</h1>";
	echo "<table>";
	echo "<tr>";
	echo "<th>Sun</th>";
	echo "<th>Mon</th>";
	echo "<th>Tue</th>";
	echo "<th>Wed</th>";
	echo "<th>Thu</th>";
	echo "<th>Fri</th>";
	echo "<th>Sat</th>";
	echo "</tr>";
	
	// Create an array to store the days of the month
	$days = array();
	
	// Fill the array with the days of the month
	for ($i = 1; $i <= $num_days; $i++) {
	    $timestamp = mktime(0, 0, 0, $month, $i, $year);
	    $day_num = date('j', $timestamp);
	    $day_name = date('D', $timestamp);
	    $days[$i] = array(
	        "num" => $day_num,
	        "name" => $day_name
	    );
	}
	
	// Create the table rows with the days of the month
	$row = 1;
	$col = 0;
	foreach ($days as $day) {
	    // Start a new row if it's the first day of the week
	    if ($col == 0) {
	        echo "<tr>";
	    }
	    
	    // Add empty cells for days before the first day of the month
	    if ($col < date('w', $first_day)) {
	        echo "<td></td>";
	    } else {
	        // Add the day number and mark today's date
	        $class = "";
	        if ($day['num'] == date('j') && $month == date('m') && $year == date('Y')) {
	            $class = "today";
	        }
	        echo "<td class=\"$class\">" . $day['num'] . "</td>";
	        
	        // Check if the user has any events for this day
	        $event_count = 0;
	        foreach ($calendars as $calendar) {
	            $sql = "SELECT COUNT(*) as count FROM events WHERE calendar_id = " . $calendar['id'] . " AND DAY(date) = " . $day['num'];
	            $result = $conn->query($sql);
	            $row = $result->fetch_assoc();
	            $event_count += $row['count'];
	        }
	        
	        // Display the number of events for this day
	        if ($event_count > 0) {
	            echo "<br>" . $event_count . " event(s)";
	        }
	    }
	    
	    // End the row if it's the last day of the week
	    if ($col == 6) {
	        echo "</tr>";
	        $row++;
	        $col = 0;
	    } else {
	        $col++;
	    }
	    
	    // Add empty cells for days after the last day of the month
	    if ($col > 0) {
	        for ($i = $col; $i < 7; $i++) {
	            echo "<td></td>";
	        }
	        echo "</tr>";
	    
	    
	    echo "</table>";
	    echo "<p><a href='dashboard.php'>Back to Dashboard</a></p>";
	    $conn->close();
	} else {
	    // If the user isn't logged in, redirect to the login page
	    header("Location: login.php");
	    exit();
	}
}
?>
	    
	    
	    
