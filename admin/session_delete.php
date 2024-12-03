<?php
session_start(); // Start the session to store messages
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM sessions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Session deleted successfully!';
    } else {
        $_SESSION['error'] = 'Something went wrong while deleting the session.';
    }

    $stmt->close();
    $conn->close();
    header('Location: sessions.php'); // Redirect back to sessions.php
    exit();
}
?>
