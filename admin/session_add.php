<?php
session_start(); // Start the session to store messages
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $course = trim($_POST['course']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);

    $sql = "INSERT INTO sessions (title, course, start_date, end_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $title, $course, $start_date, $end_date);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Session added successfully!';
    } else {
        $_SESSION['error'] = 'Something went wrong while adding the session.';
    }

    $stmt->close();
    $conn->close();
    header('Location: sessions.php'); // Redirect to sessions.php
    exit();
}
?>