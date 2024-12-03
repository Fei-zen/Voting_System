<?php
// Includes
include 'includes/session.php';
include 'includes/header.php';

// Get session_id from the URL
if (!isset($_GET['session_id'])) {
    $_SESSION['error'] = 'No election session selected.';
    header('location: election_status.php');
    exit();
}
$session_id = $_GET['session_id'];

// Query to fetch election data (positions, candidates, and votes tally)
$sql = "SELECT positions.id AS position_id, positions.description AS position,
               candidates.id AS candidate_id, 
               CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name,
               COALESCE(COUNT(votes.id), 0) AS vote_count
        FROM positions
        LEFT JOIN candidates ON candidates.position_id = positions.id
        LEFT JOIN votes ON votes.candidate_id = candidates.id
        WHERE positions.session_id = ?
        GROUP BY positions.id, candidates.id
        ORDER BY positions.id, candidates.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

// Organize data by position
$data = [];
while ($row = $result->fetch_assoc()) {
    $position = $row['position'];
    if (!isset($data[$position])) {
        $data[$position] = [];
    }
    $data[$position][] = [
        'candidate_name' => $row['candidate_name'],
        'vote_count' => $row['vote_count']
    ];
}

// Fetch election title
$sql_title = "SELECT title FROM sessions WHERE id = ?";
$stmt_title = $conn->prepare($sql_title);
$stmt_title->bind_param("i", $session_id);
$stmt_title->execute();
$title_result = $stmt_title->get_result();
$election_title = $title_result->fetch_assoc()['title'];
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <h1>
        Election Progress
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="election_status.php">Election Status</a></li>
        <li class="active">Election Progress</li>
      </ol>
    </section>

    <!-- Main Content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <h3>Election: <?php echo htmlspecialchars($election_title); ?></h3>
        </div>
      </div>

      <?php foreach ($data as $position => $candidates): ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title"><?php echo htmlspecialchars($position); ?></h3>
              </div>
              <div class="box-body">
                <!-- Table for Vote Tally -->
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Candidate</th>
                      <th>Votes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($candidates as $candidate): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($candidate['candidate_name']); ?></td>
                        <td><?php echo htmlspecialchars($candidate['vote_count']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <!-- Histogram -->
                <canvas id="<?php echo str_replace(' ', '_', $position); ?>" width="400" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>

<!-- Scripts -->
<?php include 'includes/scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  <?php foreach ($data as $position => $candidates): ?>
    const ctx_<?php echo str_replace(' ', '_', $position); ?> = document.getElementById('<?php echo str_replace(' ', '_', $position); ?>').getContext('2d');
    new Chart(ctx_<?php echo str_replace(' ', '_', $position); ?>, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode(array_column($candidates, 'candidate_name')); ?>,
        datasets: [{
          label: 'Votes',
          data: <?php echo json_encode(array_column($candidates, 'vote_count')); ?>,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  <?php endforeach; ?>
</script>
</body>
</html>
