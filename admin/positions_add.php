<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['add'])) {
    $session_id = intval($_POST['session_id']); // Get session_id from the form
    $description = trim($_POST['description']);
    $max_vote = intval($_POST['max_vote']);
    $priority = intval($_POST['priority']);

    // Insert position into the positions table
    $sql = "INSERT INTO positions (session_id, description, max_vote, priority) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $session_id, $description, $max_vote, $priority);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Position added successfully!';
    } else {
        $_SESSION['error'] = 'Something went wrong while adding the position.';
    }

    $stmt->close();
    $conn->close();
    header('Location: positions.php'); // Redirect back to positions list
    exit();
} else {
    $_SESSION['error'] = 'Fill out the form first.';
    header('Location: positions.php');
    exit();
}
?>
