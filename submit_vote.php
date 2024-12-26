<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['vote'])) {
    $votes_submitted = false; // Track if any votes were submitted
    $errors = []; // Store error messages
    $sql_array = []; // To store SQL queries for batch execution

    // Check if voter is logged in
    if (!isset($_SESSION['voter_id'])) {
        $_SESSION['error'] = 'You must log in first.';
        header('Location: home.php');
        exit();
    }

    $voter_id = intval($_SESSION['voter_id']); // Retrieve and sanitize voters_id from session

    // Validate session_id
    if (!isset($_POST['session_id']) || empty($_POST['session_id'])) {
        $_SESSION['error'][] = 'Invalid election session.';
        header('location: home.php');
        exit();
    }
    $session_id = intval($_POST['session_id']); // Sanitize session_id

    // Fetch positions for this session
    $sql = "SELECT * FROM positions WHERE session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    $positions = $stmt->get_result();

    if ($positions->num_rows === 0) {
        $_SESSION['error'][] = 'No positions found for this election.';
        header('location: home.php');
        exit();
    }

    // Loop through positions to process votes
    while ($row = $positions->fetch_assoc()) {
        $position_id = intval($row['id']);
        $max_vote = intval($row['max_vote']);

        // Check if there are votes for the current position
        if (isset($_POST["position_$position_id"])) {
            $votes_submitted = true; // At least one vote was cast

            if ($max_vote > 1) { // Multi-vote (checkbox)
                if (is_array($_POST["position_$position_id"])) {
                    if (count($_POST["position_$position_id"]) > $max_vote) {
                        $errors[] = 'You can only select up to ' . $max_vote . ' candidates for ' . htmlspecialchars($row['description']);
                    } else {
                        foreach ($_POST["position_$position_id"] as $candidate_id) {
                            $candidate_id = intval($candidate_id); // Sanitize input
                            $sql_array[] = "INSERT INTO votes (voters_id, session_id, position_id, candidate_id) VALUES ('$voter_id', '$session_id', '$position_id', '$candidate_id')";
                        }
                    }
                } else {
                    $errors[] = 'Invalid input for ' . htmlspecialchars($row['description']);
                }
            } else { // Single-vote (radio button)
                $candidate_id = intval($_POST["position_$position_id"]); // Sanitize input
                $sql_array[] = "INSERT INTO votes (voters_id, session_id, position_id, candidate_id) VALUES ('$voter_id', '$session_id', '$position_id', '$candidate_id')";
            }
        } else {
            $errors[] = 'No candidate selected for ' . htmlspecialchars($row['description']);
        }
    }

    // If no votes were submitted
    if (!$votes_submitted) {
        $errors[] = 'Please vote for at least one candidate.';
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = $errors; // Save errors in the session
        header("location: vote.php?session_id=$session_id"); // Redirect to vote.php
        exit();
    }
    

    // Insert votes into the database if no errors
    if (empty($errors)) {
        $error = false;
        foreach ($sql_array as $sql_row) {
            if (!$conn->query($sql_row)) {
                $_SESSION['error'][] = "Database error: " . $conn->error;
                $error = true;
                break;
            }
        }

        if (!$error) {
            unset($_SESSION['post']); // Clear form data
            $_SESSION['success'] = 'Ballot submitted successfully.';
        }
    } else {
        $_SESSION['error'] = $errors; // Save errors in session
    }
} else {
    $_SESSION['error'][] = 'Invalid vote submission.';
}

header('location: home.php');
?>
