<?php
session_start(); // Start the session to store messages
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $course = $_POST['course'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "UPDATE sessions SET title = ?, course = ?, start_date = ?, end_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $title, $course, $start_date, $end_date, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Session updated successfully!';
    } else {
        $_SESSION['error'] = 'Something went wrong while updating the session.';
    }

    $stmt->close();
    $conn->close();
    header('Location: sessions.php'); // Redirect back to sessions.php
    exit();
}
?>
