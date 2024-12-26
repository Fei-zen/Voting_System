<?php
include 'includes/session.php';

if (isset($_POST['add'])) {
    // Retrieve input values from the form
    $session_id = $_POST['session_id'];
    $position_id = $_POST['position'];
    $voter_id = $_POST['voter_id']; // Voter ID (from candidate search)
    $platform = $_POST['platform'];

    // Get the first name and last name from the voter or candidate table using the voter_id
    $sql = "SELECT firstname, lastname FROM voters WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();

    if (empty($firstname) || empty($lastname)) {
        $_SESSION['error'] = 'Invalid voter ID or candidate information.';
        header('location: candidates.php');
        exit();
    }

    // Handle optional photo upload
    $filename = $_FILES['photo']['name'];
    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $filename);
    } else {
        $filename = null; // No photo uploaded
    }

    // Insert into candidates table
    $sql = "INSERT INTO candidates (session_id, position_id, voter_id, firstname, lastname, photo, platform) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissss", $session_id, $position_id, $voter_id, $firstname, $lastname, $filename, $platform);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Candidate added successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Fill up the form first';
}

header('location: candidates.php');
?>
