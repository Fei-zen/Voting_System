<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'];

    // SQL query to search voters' first or last names
    $sql = "SELECT id, firstname, lastname FROM voters 
            WHERE firstname LIKE ? OR lastname LIKE ? 
            ORDER BY firstname ASC LIMIT 10";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $term . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare response data
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => $row['firstname'] . ' ' . $row['lastname'], // Displayed text
            'value' => $row['firstname'] . ' ' . $row['lastname'], // Input value
            'id' => $row['id'] // Voter ID
        ];
    }

    // Return JSON response
    echo json_encode($data);
    exit();
}
?>
