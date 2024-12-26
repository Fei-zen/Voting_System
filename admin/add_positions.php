<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['add'])) {
    // Step 1: Collect and validate input data
    $description = trim($_POST['description']); // Get dropdown value
    $custom_description = isset($_POST['custom_description']) ? trim($_POST['custom_description']) : ''; // Get custom input
    $max_vote = isset($_POST['max_vote']) ? intval($_POST['max_vote']) : 0;
    $priority = isset($_POST['priority']) ? intval($_POST['priority']) : 0;

    // Validate inputs
    if (empty($description) || $max_vote <= 0 || $priority <= 0) {
        $_SESSION['error'] = 'Please fill in all required fields correctly.';
        header('Location: positions.php');
        exit();
    }

    // Step 2: Determine the final value for the description
    if ($description === 'other' && !empty($custom_description)) {
        $description = $custom_description; // Use custom input
    } elseif ($description === 'other') {
        $_SESSION['error'] = 'You selected "Other" but did not provide a custom position name.';
        header('Location: positions.php');
        exit();
    }

    // Step 3: Check for duplicates in the database
    $check_sql = "SELECT id FROM positions WHERE description = ?";
    $check_stmt = $conn->prepare($check_sql);

    if ($check_stmt) {
        $check_stmt->bind_param("s", $description);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $_SESSION['error'] = 'Position "' . htmlspecialchars($description) . '" already exists.';
            $check_stmt->close();
            header('Location: positions.php');
            exit();
        }
        $check_stmt->close();
    } else {
        $_SESSION['error'] = 'Database error: Could not prepare duplicate check statement.';
        header('Location: positions.php');
        exit();
    }

    // Step 4: Insert data into the database
    $sql = "INSERT INTO positions (description, max_vote, priority) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sii", $description, $max_vote, $priority);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'New position added successfully.';
        } else {
            $_SESSION['error'] = 'Database error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = 'Database error: Could not prepare statement.';
    }

    // Redirect back to positions page
    header('Location: positions.php');
    exit();
} else {
    $_SESSION['error'] = 'Invalid form submission.';
    header('Location: positions.php');
    exit();
}
?>
