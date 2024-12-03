<?php
// Include the database connection
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    // Retrieve input values
    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation to ensure no field is empty
    if (empty($name) || empty($lastname) || empty($username) || empty($password)) {
        die("Please fill out all fields.");
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into the database
    $sql = "INSERT INTO admin (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $lastname, $username, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to success page or dashboard
        header("Location: admin/home.php");
        exit();
    } else {
        die("Error inserting data: " . $conn->error);
    }
} else {
    die("Invalid request.");
}
