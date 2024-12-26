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
      <h1>
        Ballot List
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ballot</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
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
                <tr class="bg-dark">
                  <th>Title</th>
                  <th>Course</th>
                  <th>View Ballot</th>

                </tr>
                </thead>
                <tbody>
                    <?php
                      // Fetch sessions data from database
                      $sql = "SELECT * FROM sessions";
                      $query = $conn->query($sql);
                      while($row = $query->fetch_assoc()){
                        echo "
                          <tr>
                              <td>".$row['title']."</td>
                              <td>".$row['course']."</td>
                              <td><a href='ballot.php?session_id=".$row['id']."' class='btn btn-info btn-sm btn-flat'><i class='fa fa-search'></i> View</a></td>
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


</div>
<?php include 'includes/scripts.php'; ?>
<script class="content">
  <?php
    if (isset($_SESSION['error'])) {
        echo "
        <div class='alert alert-danger alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h4><i class='icon fa fa-warning'></i> Error!</h4>
          {$_SESSION['error']}
        </div>
        ";
        unset($_SESSION['error']); // Clear the message after displaying
    }

    if (isset($_SESSION['success'])) {
        echo "
        <div class='alert alert-success alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h4><i class='icon fa fa-check'></i> Success!</h4>
          {$_SESSION['success']}
        </div>
        ";
        unset($_SESSION['success']); // Clear the message after displaying
    }
  ?>
</script>
</body>
</html>
