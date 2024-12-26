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

// Fetch positions and candidates for the session
$positionSql = "SELECT * FROM positions WHERE session_id = ? ORDER BY priority ASC";
$positionStmt = $conn->prepare($positionSql);
$positionStmt->bind_param("i", $session_id);
$positionStmt->execute();
$positions = $positionStmt->get_result();
$positionStmt->close();
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

              <?php while ($position = $positions->fetch_assoc()): ?>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>
                                <b>Position: <?php echo $position['description']; ?></b> (Max Votes: <?php echo $position['max_vote']; ?>)
                            </div>
                            <div class='panel-body'>
                                <?php
                                $candidatesQuery = $conn->prepare("SELECT * FROM candidates WHERE position_id = ?");
                                $candidatesQuery->bind_param("i", $position['id']);
                                $candidatesQuery->execute();
                                $candidates = $candidatesQuery->get_result();
                                $candidatesQuery->close();
                                ?>
                                <div class="row">
                                    <?php while ($candidate = $candidates->fetch_assoc()): ?>
                                        <div class="col-md-4 text-center">
                                            <!-- Display candidate photo or a placeholder -->
                                            <?php if (!empty($candidate['photo'])): ?>
                                                <img src="images/<?php echo htmlspecialchars($candidate['photo']); ?>" alt="Candidate Photo" class="img-thumbnail" width="100" height="100">
                                            <?php else: ?>
                                                <img src="images/profile.jpg" alt="Placeholder" class="img-thumbnail" width="100" height="100">
                                            <?php endif; ?>
                                            
                                            <div>
                                                <b><?php echo $candidate['firstname'] . ' ' . $candidate['lastname']; ?></b>
                                                <br>
                                                <small><?php echo nl2br(htmlspecialchars($candidate['platform'])); ?></small>
                                            </div>
                                            <input 
                                                type="checkbox" 
                                                name="position_<?php echo $position['id']; ?>[]" 
                                                value="<?php echo $candidate['id']; ?>" 
                                                class="position_<?php echo $position['id']; ?>_vote form-check-input">
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Enforce max_vote restriction using JavaScript -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const maxVotes = <?php echo $position['max_vote']; ?>;
                                const checkboxes = document.querySelectorAll('.position_<?php echo $position['id']; ?>_vote');
                                
                                checkboxes.forEach(checkbox => {
                                    checkbox.addEventListener('change', function() {
                                        const checkedBoxes = document.querySelectorAll('.position_<?php echo $position['id']; ?>_vote:checked');
                                        if (checkedBoxes.length > maxVotes) {
                                            this.checked = false; // Uncheck the current box
                                            alert('You can only select up to ' + maxVotes + ' candidates for this position.');
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php endwhile; ?>
            </div>
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
