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
                    <h1 class="text-center">Election for: 
                        <?php echo htmlspecialchars($session['title']); ?> 
                    </h1>
                    <hr>
                    <form class="modal-body" method="POST" action="submit_vote.php">
                        <!-- Hidden input for session_id -->
                        <input type="hidden" name="vote" value="1"> <!-- Hidden field to trigger vote processing -->
                        <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                        

                        <?php while ($position = $positions->fetch_assoc()): ?>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <b>Position: <?php echo htmlspecialchars($position['description']); ?></b> (Max Votes: <?php echo $position['max_vote']; ?>)
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
                                                <!-- Display candidate photo or fallback -->
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
                                                
                                                <?php if ($position['max_vote'] > 1): ?>
                                                    <!-- Checkbox for multi-vote positions -->
                                                    <input 
                                                        type="checkbox" 
                                                        name="position_<?php echo $position['id']; ?>[]" 
                                                        value="<?php echo $candidate['id']; ?>" 
                                                        class="position_<?php echo $position['id']; ?>_vote form-check-input">
                                                <?php else: ?>
                                                    <!-- Radio button for single-vote positions -->
                                                    <input 
                                                        type="radio" 
                                                        name="position_<?php echo $position['id']; ?>" 
                                                        value="<?php echo $candidate['id']; ?>" 
                                                        class="position_<?php echo $position['id']; ?>_vote form-check-input">
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($position['max_vote'] > 1): ?>
                                <!-- Enforce max_vote restriction with JavaScript -->
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
                            <?php endif; ?>
                        <?php endwhile; ?>
                        <div  class="space">
                            <div>
                                <button class='btn btn-primary btn-md vote-btn' type="submit">Submit</button>
                            
                                <button id="preview_btn" class='btn btn-primary btn-md vote-btn'>Preview</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Error Modal -->
<div class="modal fade" id="error_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-danger">Warning!</h4>
            </div>
            <div class="modal-body">
                <ul id="error_list"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Populate and Show Error Modal -->
<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const errorList = document.getElementById('error_list');
        const errors = <?php echo json_encode($_SESSION['error']); ?>;

        // Populate the error list
        errors.forEach(function (error) {
            const li = document.createElement('li');
            li.textContent = error;
            errorList.appendChild(li);
        });

        // Show the modal
        $('#error_modal').modal('show');
    });
</script>
<?php unset($_SESSION['error']); // Clear errors after displaying ?>
<?php endif; ?>
<?php include 'includes/ballot_modal.php'; ?>
<?php include 'includes/scripts.php'; ?>
<script>
document.getElementById('preview_btn').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent form submission

    console.log("Preview button clicked."); // Debug log

    const previewBody = document.getElementById('preview_body');
    previewBody.innerHTML = ''; // Clear previous content

    // Loop through all positions to collect votes
    document.querySelectorAll('.panel').forEach(function (panel) {
        const positionName = panel.querySelector('.panel-heading b').textContent; // Get position name
        console.log(`Processing position: ${positionName}`); // Debug log

        const maxVotesText = panel.querySelector('.panel-heading').textContent.match(/\(Max Votes: (\d+)\)/);
        const maxVotes = maxVotesText ? parseInt(maxVotesText[1]) : 1;

        const selectedVotes = [];
        if (maxVotes > 1) {
            // Handle multi-vote (checkboxes)
            panel.querySelectorAll('input[type="checkbox"]:checked').forEach(function (checkbox) {
                const candidateLabel = checkbox.closest('.text-center').querySelector('b').textContent.trim();
                console.log(`Checkbox vote selected: ${candidateLabel}`); // Debug log
                selectedVotes.push(candidateLabel);
            });
        } else {
            // Handle single-vote (radio buttons)
            const selectedRadio = panel.querySelector('input[type="radio"]:checked');
            if (selectedRadio) {
                const candidateLabel = selectedRadio.closest('.text-center').querySelector('b').textContent.trim();
                console.log(`Radio vote selected: ${candidateLabel}`); // Debug log
                selectedVotes.push(candidateLabel);
            }
        }

        // Add the position and selected candidates to the preview
        if (selectedVotes.length > 0) {
            const positionHTML = `
                <p>
                    <b> ${positionName}</b>
                    <ul>
                        ${selectedVotes.map(vote => `<li>${vote}</li>`).join('')}
                    </ul>
                </p>
            `;
            console.log(`Adding position to modal: ${positionName}`); // Debug log
            previewBody.innerHTML += positionHTML;
        }
    });

    // Check if any votes were collected; otherwise, display a placeholder
    if (!previewBody.innerHTML.trim()) {
        previewBody.innerHTML = '<p>No votes selected.</p>';
        console.log("No votes selected."); // Debug log
    }

    // Show the modal
    $('#preview_modal').modal('show');
});
</script>
</body>
</html>