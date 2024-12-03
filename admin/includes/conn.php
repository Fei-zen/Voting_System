<?php
	$conn = new mysqli('localhost', 'root', '', 'votesystem');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}


	$host = 'localhost';  // Database host
	$db = 'votesystem';   // Database name
	$user = 'root';       // Database username
	$pass = '';           // Database password (leave blank for XAMPP)

	$conn = new mysqli($host, $user, $pass, $db);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>