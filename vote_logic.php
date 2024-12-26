<?php
include 'includes/session.php';
include 'includes/conn.php'; // Database connection

// Check if voter is logged in
if (!isset($_SESSION['voter_id'])) {
    $_SESSION['error'] = 'You must log in first to vote.';
    header('Location: home.php');
    exit();
}

// Retrieve the voter's ID from the session
$voter_id = $_SESSION['voter_id'];

// Check if session_id is provided in the URL
if (!isset($_GET['session_id']) || empty($_GET['session_id'])) {
    $_SESSION['error'] = 'Election session not specified.';
    header('Location: home.php');
    exit();
}

$session_id = intval($_GET['session_id']);

// Fetch voter details
$voterQuery = $conn->prepare("SELECT course FROM voters WHERE voters_id = ?");
$voterQuery->bind_param("s", $voter_id); // Use 's' for string type
$voterQuery->execute();
$voterResult = $voterQuery->get_result();
$voter = $voterResult->fetch_assoc();
$voterQuery->close();

// If voter not found
if (!$voter) {
    $_SESSION['error'] = 'Voter not found in the database.';
    header('Location: home.php');
    exit();
}

$voter_course = $voter['course']; // Voter's course

// Fetch election session details, including start_date and end_date
$sessionQuery = $conn->prepare("SELECT course, title, start_date, end_date FROM sessions WHERE id = ?");
$sessionQuery->bind_param("i", $session_id);
$sessionQuery->execute();
$sessionResult = $sessionQuery->get_result();
$session = $sessionResult->fetch_assoc();
$sessionQuery->close();

// If election session not found
if (!$session) {
    $_SESSION['error'] = 'Election session not found.';
    header('Location: home.php');
    exit();
}

$election_course = $session['course']; // Election's course
$start_date = $session['start_date']; // Start date
$end_date = $session['end_date']; // End date

// Eligibility Logic
if (empty($election_course)) {
    $_SESSION['error'] = 'Election course is not specified.';
    header('Location: home.php');
    exit();
}

if ($election_course !== 'CICT' && $election_course !== $voter_course) {
    $_SESSION['error'] = 'You are not eligible to vote in this election.';
    header('Location: home.php');
    exit();
}

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

// Check if the election is open based on start_date and end_date
$current_date = date('Y-m-d H:i:s'); // Current date and time

if ($current_date < $start_date) {
    $_SESSION['error'] = 'This election has not started yet.';
    header('Location: home.php');
    exit();
}

if ($current_date > $end_date) {
    $_SESSION['error'] = 'This election has already ended.';
    header('Location: home.php');
    exit();
}

// If eligible and the election is open, redirect to the voting page
header("Location: vote.php?session_id=$session_id");
exit();
?>
