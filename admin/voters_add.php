<?php
include 'includes/session.php';

if (isset($_POST['add'])) {
    $voter_id = $_POST['voter_id']; // Get Voter ID from input
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course']; // Get course input
    $filename = $_FILES['photo']['name'];

    // Hash the Voter ID to use as the password
    $password = password_hash($voter_id, PASSWORD_DEFAULT);

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
    }

    // Check if Voter ID already exists
    $sql_check = "SELECT * FROM voters WHERE voters_id = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Voter ID already exists. Please choose a unique ID.';
    } else {
        // Insert the new voter into the database
        $sql = "INSERT INTO voters (voters_id, password, firstname, lastname, course, photo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $voter_id, $password, $firstname, $lastname, $course, $filename);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Voter added successfully';
        } else {
            $_SESSION['error'] = $conn->error;
        }
    }
} else {
    $_SESSION['error'] = 'Fill up add form first';
}

header('location: voters.php');
?>
