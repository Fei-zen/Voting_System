<?php
session_start(); // Start the session to store messages
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $course = trim($_POST['course']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);

    // Validate the dates
    if (strtotime($end_date) < strtotime($start_date)) {
        $_SESSION['error'] = 'End date cannot be earlier than the start date.';
        header('Location: sessions.php'); // Redirect back to the add session form
        exit();
    }

    // Insert data into the database
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
