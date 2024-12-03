<?php
	session_start();
	include 'includes/conn.php';

	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM admin WHERE username = '$username'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Cannot find account with the username';
		}
		else{
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['admin'] = $row['id'];
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
			}
		}
		
	}
	else{
		$_SESSION['error'] = 'Input admin credentials first';
	}

	header('location: index.php');

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$firstname = trim($_POST['firstname']);
		$lastname = trim($_POST['lastname']);
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
	
		
		if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
			echo "Please put your credentials here.";
		} else {
			
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
	
			
			$sql = "INSERT INTO admin (firstname, lastname, username, password) VALUES (:firstname, :lastname, :username, :password)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':firstname', $firstname);
			$stmt->bindParam(':lastname', $lastname);
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':password', $hashedPassword);
	
			if ($stmt->execute()) {
				header("Location: home.php"); 
				exit;
			} else {
				echo "Error: Could not insert into database.";
			}
		}
	}

?>