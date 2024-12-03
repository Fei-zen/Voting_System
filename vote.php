<?php
include 'includes/session.php';
include 'includes/conn.php';

if (!isset($_GET['session_id']) || empty($_GET['session_id'])) {
    $_SESSION['error'] = 'Election session not specified.';
    header('Location: home.php');
    exit();
}

$session_id = intval($_GET['session_id']);

// Fetch election details
$sessionQuery = $conn->prepare("SELECT title FROM sessions WHERE id = ?");
$sessionQuery->bind_param("i", $session_id);
$sessionQuery->execute();
$session = $sessionQuery->get_result()->fetch_assoc();
$sessionQuery->close();

if (!$session) {
    $_SESSION['error'] = 'Election session not found.';
    header('Location: home.php');
    exit();
}

// Fetch positions and candidates
$positionsQuery = $conn->prepare("SELECT * FROM positions WHERE session_id = ? ORDER BY priority ASC");
$positionsQuery->bind_param("i", $session_id);
$positionsQuery->execute();
$positions = $positionsQuery->get_result();
$positionsQuery->close();
?>

<?php include 'includes/header.php'; ?>
<section>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                <h1 class="text-center">Election Title: 
                <?php echo htmlspecialchars($session['title']); ?> 
                
                </h1>
                <hr>
                <form class="modal-body" method="POST" action="submit_vote.php">
                    <!-- Add this hidden input field -->
                    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">

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
                    <button class='btn btn-primary btn-sm vote-btn' type="submit">Submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
</html>
