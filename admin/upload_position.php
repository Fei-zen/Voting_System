<?php
session_start();
include 'includes/conn.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Ensure the uploaded file is a CSV
        if (strtolower($fileExtension) !== 'csv') {
            $_SESSION['error'] = "Please upload a valid CSV file.";
            header("Location: positions.php");
            exit();
        }

        $file = fopen($fileTmpPath, 'r');
        $rowCount = 0;
        $successCount = 0;

        while (($data = fgetcsv($file, 1000, ",")) !== false) {
            $rowCount++;

            // Skip the header row
            if ($rowCount === 1) {
                continue;
            }

            // Ensure the CSV has the correct number of columns
            if (count($data) !== 4) {
                error_log("Skipped row due to invalid column count: " . implode(", ", $data));
                continue; // Skip invalid rows
            }

            // Retrieve and sanitize data
            $session_title = trim($data[0]);
            $description = trim($data[1]);
            $maxVote = (int) trim($data[2]);
            $priority = (int) trim($data[3]);

            // Validate data
            if (empty($session_title) || empty($description) || $maxVote <= 0 || $priority <= 0) {
                error_log("Skipped invalid row: $session_title, $description, $maxVote, $priority");
                continue; // Skip invalid rows
            }

            // Fetch the session_id based on the session title
            $sessionSql = "SELECT id FROM sessions WHERE title = ?";
            $sessionStmt = $conn->prepare($sessionSql);
            $sessionStmt->bind_param("s", $session_title);
            $sessionStmt->execute();
            $sessionResult = $sessionStmt->get_result();

            if ($sessionResult->num_rows === 0) {
                error_log("Skipped row: Session title '$session_title' not found.");
                continue; // Skip rows with invalid session titles
            }

            $session = $sessionResult->fetch_assoc();
            $session_id = $session['id'];
            $sessionStmt->close();

            // Insert position into the database
            $sql = "INSERT INTO positions (session_id, description, max_vote, priority) VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE max_vote = VALUES(max_vote), priority = VALUES(priority)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $session_id, $description, $maxVote, $priority);

            if ($stmt->execute()) {
                $successCount++;
            } else {
                error_log("Failed to insert row: $description, $maxVote, $priority - Error: " . $stmt->error);
            }

            $stmt->close();
        }

        fclose($file);

        $_SESSION['success'] = "$successCount positions added successfully.";
        header("Location: positions.php");
        exit();
    } else {
        $_SESSION['error'] = "Error uploading file.";
        header("Location: positions.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: positions.php");
    exit();
}
?>
