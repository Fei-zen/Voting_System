<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    $voter_id = $_SESSION['voter_id']; // Assuming the voter is logged in

    // Fetch the voter's selected candidates
    $sql = "SELECT 
                sessions.title AS election_title,
                positions.description AS position,
                candidates.firstname AS candidate_firstname,
                candidates.lastname AS candidate_lastname
            FROM 
                votes
            JOIN 
                sessions ON votes.session_id = sessions.id
            JOIN 
                positions ON votes.position_id = positions.id
            JOIN 
                candidates ON votes.candidate_id = candidates.id
            WHERE 
                votes.voters_id = voters.id AND votes.session_id = sessions.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $voter_id, $session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $votes = [];
    while ($row = $result->fetch_assoc()) {
        $votes[] = $row;
    }

    // Return data as JSON
    echo json_encode($votes);
} else {
    echo json_encode([]);
}
?>
