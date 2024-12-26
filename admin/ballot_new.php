<?php
session_start();
include 'includes/conn.php'; // Database connection
include 'includes/session.php';

if (!isset($_GET['session_id']) || empty($_GET['session_id'])) {
    $_SESSION['error'] = 'Session ID not provided.';
    header('Location: ballot_list.php'); // Redirect to the Ballot List page
    exit();
}

$session_id = intval($_GET['session_id']);

// Fetch session details
$sessionSql = "SELECT * FROM sessions WHERE id = ?";
$sessionStmt = $conn->prepare($sessionSql);
$sessionStmt->bind_param("i", $session_id);
$sessionStmt->execute();
$session = $sessionStmt->get_result()->fetch_assoc();
$sessionStmt->close();

// Fetch candidates and positions for the session
$candidatesSql = "
    SELECT candidates.*, positions.description AS position_description, positions.max_vote
    FROM candidates
    LEFT JOIN positions ON candidates.position_id = positions.id
    WHERE candidates.session_id = ?
    ORDER BY positions.priority ASC
";
$candidatesStmt = $conn->prepare($candidatesSql);
$candidatesStmt->bind_param("i", $session_id);
$candidatesStmt->execute();
$candidates = $candidatesStmt->get_result();
$candidatesStmt->close();

// Debugging: Check if candidates and positions are found
// if ($candidates->num_rows === 0) {
//     echo "No candidates or positions found for this session."; // Debugging line
// } else {
//     echo "Candidates found: " . $candidates->num_rows . "<br>";
// }

?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Sample Ballot
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="sam_ballot.php">Ballot List</a></li>
        <li class="active">Sample Ballot</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <!-- Display Election Title -->
                        <h3 class="text-center">Election Title: <?php echo $session['title']; ?> (<?php echo $session['course']; ?>)</h3>
                        <hr>

                        <?php 
                        // Group candidates by position
                        $current_position = null;
                        while ($candidate = $candidates->fetch_assoc()):
                        // Start a new position section if we encounter a new position
                        if ($current_position !== $candidate['position_description']): 
                            if ($current_position !== null) {
                                echo "</div></div>"; // Close the previous position's candidate list
                            }
                            $current_position = $candidate['position_description'];
                            ?>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>
                                <b>Position: <?php echo htmlspecialchars($current_position); ?></b> (Max Votes: <?php echo htmlspecialchars($candidate['max_vote']); ?>)
                            </div>
                            <div class='panel-body'>
                                <div class="row">
                                        <?php 
                                        endif;
                                            ?>
                        
                                        <div class="col-md-4 text-center">
                                                    <!-- Display candidate photo or a placeholder -->
                                                    <?php if (!empty($candidate['photo'])): ?>
                                                        <img src="images/<?php echo htmlspecialchars($candidate['photo']); ?>" alt="Candidate Photo" class="img-thumbnail" width="100" height="100">
                                                    <?php else: ?>
                                                        <img src="images/profile.jpg" alt="Placeholder" class="img-thumbnail" width="100" height="100">
                                                    <?php endif; ?>
                                        
                                                <div>
                                                    <b><?php echo htmlspecialchars($candidate['firstname'] . ' ' . $candidate['lastname']); ?></b>
                                                    <br>
                                                    <small><?php echo nl2br(htmlspecialchars($candidate['platform'])); ?></small>
                                                </div>
                                                <input 
                                                    type="checkbox" 
                                                    name="position_<?php echo $candidate['position_id']; ?>[]" 
                                                    value="<?php echo $candidate['id']; ?>" 
                                                    class="position_<?php echo $candidate['position_id']; ?>_vote form-check-input">
                                        </div>
                    
                                    <?php endwhile; ?>

                                </div> <!-- End of row for candidates -->
                            </div> <!-- End of panel-body -->
                        </div> <!-- End of panel for this position -->
                    </div> <!-- End of box-body -->
                </div>
            </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
