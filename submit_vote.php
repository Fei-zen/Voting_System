<?php
session_start();
include 'includes/conn.php';
include 'includes/session.php';

// Ensure voter is logged in
if (!isset($_SESSION['voter_id'])) {
    $_SESSION['error'] = 'Please log in to vote.';
    header('Location: login.php');
    exit();
}

$voter_id = $_SESSION['voter_id']; // Correct voter_id retrieval

// Ensure session_id is passed in the form
if (!isset($_POST['session_id']) || empty($_POST['session_id'])) {
    $_SESSION['error'] = 'Election session not specified.';
    header('Location: vote.php'); // Redirect back to voting page
    exit();
}

$session_id = intval($_POST['session_id']);

// Check if voter already voted for this session
$checkSql = "SELECT * FROM votes WHERE voters_id = ? AND session_id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $voter_id, $session_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$checkStmt->close();

if ($checkResult->num_rows > 0) {
    $_SESSION['error'] = 'You have already voted in this election.';
    header('Location: home.php');
    exit();
}

// Process each position and save the vote
foreach ($_POST as $key => $candidates) {
    if (strpos($key, 'position_') === 0) { // Check if the key represents a position
        $position_id = intval(str_replace('position_', '', $key));

        foreach ($candidates as $candidate_id) {
            $candidate_id = intval($candidate_id);

            $voteSql = "INSERT INTO votes (voters_id, session_id, position_id, candidate_id) VALUES (?, ?, ?, ?)";
            $voteStmt = $conn->prepare($voteSql);
            $voteStmt->bind_param("iiii", $voter_id, $session_id, $position_id, $candidate_id);
            $voteStmt->execute();
            $voteStmt->close();
        }
    }
}

$_SESSION['success'] = 'Your vote has been successfully submitted!';
header('Location: home.php');
exit();
