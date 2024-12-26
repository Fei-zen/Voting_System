<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['vote'])) {
    if (count($_POST) == 1) { // Only the session_id is present
        $_SESSION['error'][] = 'Please vote for at least one candidate.';
    } else {
        $_SESSION['post'] = $_POST;
        $sql = "SELECT * FROM positions";
        $query = $conn->query($sql);
        $error = false;
        $sql_array = array();

        while ($row = $query->fetch_assoc()) {
            $position = slugify($row['description']);
            $pos_id = $row['id'];

            // Check if votes are submitted for this position
            if (isset($_POST[$position])) {
                if ($row['max_vote'] > 1) {
                    // Multi-vote: Handle checkbox input
                    if (!is_array($_POST[$position])) {
                        $error = true;
                        $_SESSION['error'][] = 'Invalid submission format for ' . $row['description'];
                        continue;
                    }

                    if (count($_POST[$position]) > $row['max_vote']) {
                        $error = true;
                        $_SESSION['error'][] = 'You can only choose up to ' . $row['max_vote'] . ' candidates for ' . $row['description'];
                    } else {
                        foreach ($_POST[$position] as $candidate_id) {
                            $candidate_id = intval($candidate_id); // Sanitize input
                            $sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id) VALUES ('" . $voter['id'] . "', '$candidate_id', '$pos_id')";
                        }
                    }
                } else {
                    // Single-vote: Handle radio button input
                    $candidate_id = intval($_POST[$position]); // Sanitize input
                    $sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id) VALUES ('" . $voter['id'] . "', '$candidate_id', '$pos_id')";
                }
            }
        }

        if (!$error) {
            // Execute all the SQL queries for votes
            foreach ($sql_array as $sql_row) {
                if (!$conn->query($sql_row)) {
                    $_SESSION['error'][] = $conn->error;
                    $error = true;
                    break;
                }
            }

            if (!$error) {
                unset($_SESSION['post']);
                $_SESSION['success'] = 'Ballot Submitted Successfully.';
            }
        }
    }
} else {
    $_SESSION['error'][] = 'Select candidates to vote first.';
}

header('location: home.php');
