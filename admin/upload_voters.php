<?php
// Start the session to store messages
session_start();

// Include database connection
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    // Check if a file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Ensure the uploaded file is a CSV
        if ($fileExtension === 'csv') {
            $fileProcessedSuccessfully = false; // Initialize variable
            $file = fopen($fileTmpPath, 'r');
            $rowCount = 0;
            $successCount = 0;

            // Define valid courses
            $validCourses = ['BSCS', 'BSIS', 'BSIT', 'BTVTEd', 'CICT']; // Updated "COMSOC" to "CICT"

            // Read the CSV file line by line
            while (($data = fgetcsv($file, 1000, ",")) !== false) {
                $rowCount++;

                // Skip the header row
                if ($rowCount === 1) {
                    continue;
                }

                // Ensure the CSV has the correct number of columns
                if (count($data) !== 4) {
                    continue; // Skip invalid rows
                }

                // Retrieve and sanitize data
                $firstname = trim($data[0]);
                $lastname = trim($data[1]);
                $idNumber = trim($data[2]);
                $course = trim($data[3]);

                // Default to "CICT" if the course is empty or invalid
                if (!isset($course) || $course === '' || !in_array($course, $validCourses)) {
                    $course = 'CICT';
                }

                // Use idNumber for both voters_id and password
                $votersId = $idNumber;
                $hashedPassword = password_hash($idNumber, PASSWORD_BCRYPT); // Hash the password

                // Check for duplicate voters_id
                $checkSql = "SELECT COUNT(*) FROM voters WHERE voters_id = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bind_param("s", $votersId);
                $checkStmt->execute();
                $checkStmt->bind_result($count);
                $checkStmt->fetch();
                $checkStmt->close(); // Free the prepared statement

                if ($count > 0) {
                    // Skip duplicate voters_id
                    continue;
                }

                // Insert voter data into the database
                $insertSql = "INSERT INTO voters (voters_id, firstname, lastname, password, course) VALUES (?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("sssss", $votersId, $firstname, $lastname, $hashedPassword, $course);

                if ($insertStmt->execute()) {
                    $successCount++;
                    $fileProcessedSuccessfully = true; // Set to true after a successful insert
                }
                $insertStmt->close(); // Close the insert statement
            }

            fclose($file);

            if ($fileProcessedSuccessfully) {
                // Set success message and redirect
                $_SESSION['success'] = "$successCount voters added successfully!";
                header("Location: voters.php");
                exit();
            } else {
                $_SESSION['error'] = "No valid records were processed.";
                header("Location: voters.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Please upload a valid CSV file.";
            header("Location: voters.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error uploading file.";
        header("Location: voters.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: voters.php");
    exit();
}
?>
