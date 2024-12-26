<?php
include 'includes/session.php'; // Include session handling for logged-in user
include 'includes/conn.php'; // Include database connection

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'An unknown error occurred', 'votes' => []];

try {
    // Step 1: Ensure voter is logged in through session
    if (!isset($_SESSION['voter_id'])) {
        $response['message'] = 'You must be logged in to view this data.';
        echo json_encode($response);
        exit();
    }

    // Step 2: Use logged-in voter's ID
    $voter_id = intval($_SESSION['voter_id']);

    // Step 3: Validate the session_id parameter
    if (!isset($_GET['session_id']) || empty($_GET['session_id'])) {
        $response['message'] = 'Election session ID not provided.';
        echo json_encode($response);
        exit();
    }

    $session_id = intval($_GET['session_id']); // Election session ID

    // Step 4: Check voter eligibility by matching courses
    $eligibility_query = "
        SELECT s.title AS election_title, s.course AS election_course, v.course AS voter_course
        FROM sessions s
        JOIN voters v ON (
            (TRIM(LOWER(s.course)) = 'cict' AND TRIM(LOWER(v.course)) IN ('bscs', 'bsis', 'bsit', 'btvted', 'cict'))
            OR TRIM(LOWER(s.course)) = TRIM(LOWER(v.course))
        )
        WHERE s.id = ? AND v.voters_id = ?
    ";
    $stmt = $conn->prepare($eligibility_query);
    $stmt->bind_param("ii", $session_id, $voter_id);
    $stmt->execute();
    $eligibility_result = $stmt->get_result()->fetch_assoc();

    if (!$eligibility_result) {
        // If no match is found, voter is not eligible
        $response['message'] = "You don't have votes in this election because you're not eligible.";
    } else {
        // Step 5: Check if the voter has already voted
        $votes_query = "
            SELECT v.id, p.description AS position_name, 
                   CONCAT(c.firstname, ' ', c.lastname) AS candidate_name,
                   s.title AS election_title
            FROM votes v
            JOIN positions p ON v.position_id = p.id
            JOIN candidates c ON v.candidate_id = c.id
            JOIN sessions s ON p.session_id = s.id
            WHERE v.voters_id = ? AND v.session_id = ?
        ";
        
        $stmt = $conn->prepare($votes_query);
        $stmt->bind_param("ii", $voter_id, $session_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $votes = [];
        while ($row = $result->fetch_assoc()) {
            $votes[] = $row;
        }

        if (count($votes) > 0) {
            // Voter is eligible and has voted
            $response['success'] = true;
            $response['votes'] = $votes;
        } else {
            // Voter is eligible but hasn't voted yet
            $response['message'] = "Please vote first.";
        }
    }
} catch (Exception $e) {
    // Catch any unexpected errors
    $response['message'] = $e->getMessage();
}

// Output JSON response
echo json_encode($response);
?>
