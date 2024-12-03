<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Election Status</h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Election Status</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if (isset($_SESSION['error'])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              " . $_SESSION['error'] . "
            </div>
          ";
          unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              " . $_SESSION['success'] . "
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Title</th>
                  <th>Course</th>
                  <th>Status</th>
                  <th>Progress</th>
                </thead>
                <tbody>
                  <?php
                    // Fetch sessions data from database
                    $sql = "SELECT * FROM sessions";
                    $query = $conn->query($sql);

                    while ($row = $query->fetch_assoc()) {
                      // Determine election status
                      $current_date = date('Y-m-d');
                      $start_date = $row['start_date'];
                      $end_date = $row['end_date'];
                      $status_badge = "<span class='btn btn-danger btn-sm delete btn-flat'>Closed</span>"; // Default to Closed

                      if ($current_date >= $start_date && $current_date <= $end_date) {
                        $status_badge = "<span class='btn btn-success btn-sm edit btn-flat'>Open</span>"; // Change to Open if applicable
                      }

                      // Display the row
                      echo "
                        <tr>
                          <td>{$row['title']}</td>
                          <td>{$row['course']}</td>
                          <td>{$status_badge}</td>
                          <td>
                            <a href='election_progress.php?session_id={$row['id']}' class='btn btn-primary btn-sm btn-flat'>
                            <i class='fa fa-bar-chart'></i> View
                            </a>
                          </td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/scripts.php'; ?>
</div>
</body>
</html>
