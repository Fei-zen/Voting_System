<?php
session_start();
include 'includes/conn.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Ensure the uploaded file is a CSV
        if ($fileExtension !== 'csv') {
            $_SESSION['error'] = "Please upload a valid CSV file.";
            header("Location: candidates.php");
            exit();
        }

        $file = fopen($fileTmpPath, 'r');
        $rowCount = 0;
        $successCount = 0;

        while (($data = fgetcsv($file, 1000, ",")) !== false) {
            $rowCount++;

            // Skip header row
            if ($rowCount === 1) {
                continue;
            }

            // Retrieve and sanitize data
            $electionTitle = trim($data[0]);
            $positionDescription = trim($data[1]);
            $firstname = trim($data[2]);
            $lastname = trim($data[3]);
            $photo = trim($data[4]) ?: null;
            $platform = trim($data[5]) ?: null;

            // Fetch session ID based on Election Title
            $sessionSql = "SELECT id FROM sessions WHERE title = ?";
            $sessionStmt = $conn->prepare($sessionSql);
            $sessionStmt->bind_param("s", $electionTitle);
            $sessionStmt->execute();
            $sessionStmt->bind_result($session_id);
            $sessionStmt->fetch();
            $sessionStmt->close();

            if (!$session_id) {
                // Skip rows with invalid election titles
                continue;
            }

            // Fetch position ID based on position description and session ID
            $positionSql = "SELECT id FROM positions WHERE description = ? AND session_id = ?";
            $positionStmt = $conn->prepare($positionSql);
            $positionStmt->bind_param("si", $positionDescription, $session_id);
            $positionStmt->execute();
            $positionStmt->bind_result($position_id);
            $positionStmt->fetch();
            $positionStmt->close();

            if (!$position_id) {
                // Skip rows with invalid positions
                continue;
            }

            // Insert candidate data into the database
            $candidateSql = "INSERT INTO candidates (firstname, lastname, position_id, photo, platform) VALUES (?, ?, ?, ?, ?)";
            $candidateStmt = $conn->prepare($candidateSql);
            $candidateStmt->bind_param("ssiss", $firstname, $lastname, $position_id, $photo, $platform);

            if ($candidateStmt->execute()) {
                $successCount++;
            }

            $candidateStmt->close();
        }

        fclose($file);

        $_SESSION['success'] = "$successCount candidates added successfully.";
        header("Location: candidates.php");
        exit();
    } else {
        $_SESSION['error'] = "Error uploading file.";
        header("Location: candidates.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: candidates.php");
    exit();
}
?>
