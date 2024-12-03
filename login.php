<?php
    // Start the session and include the database connection
	session_start();
	include 'includes/conn.php';

	// Check if the login form was submitted
	if(isset($_POST['login'])){
		$voter_id = $_POST['voter']; // Voter's ID
		$password = $_POST['password']; // Password
		$course = $_POST['course']; // Voter's Course

		// Validate the voter's ID against the database
		$sql = "SELECT * FROM voters WHERE voters_id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $voter_id);
		$stmt->execute();
		$result = $stmt->get_result();

		// Check if the voter exists
		if($result->num_rows < 1){
			$_SESSION['error'] = 'Cannot find voter with the ID';
		} else {
			$row = $result->fetch_assoc();

			// Verify the password
			if(password_verify($password, $row['password'])){
				// Check if the course matches
				if($row['course'] === $course){
					// Set session variables
					$_SESSION['voter_id'] = $row['voters_id'];
					$_SESSION['voter_course'] = $row['course'];
					$_SESSION['voter_name'] = $row['firstname'] . ' ' . $row['lastname']; // Optional: Display name

					// Redirect to the home page after successful login
					header('location: home.php');
					exit();
				} else {
					$_SESSION['error'] = 'Incorrect course for the voter.';
				}
			} else {
				$_SESSION['error'] = 'Incorrect password.';
			}
		}

		$stmt->close();
	} else {
		$_SESSION['error'] = 'Input voter credentials first.';
	}

	// Redirect back to the index.php page if there's an error
	header('location: index.php');
	exit();
?>
