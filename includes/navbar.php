<?php
// Ensure the session is started
if (!isset($_SESSION)) {
    session_start();
}

// Include database connection
include 'includes/conn.php';

// Check if the voter is logged in
if (isset($_SESSION['voter_id'])) {
    // Fetch voter details from the database
    $voter_id = $_SESSION['voter_id'];
    $sql = "SELECT * FROM voters WHERE voters_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $voter = $result->fetch_assoc(); // Retrieve voter information
    } else {
        $voter = null; // Voter not found, initialize as null
    }
    $stmt->close();
} else {
    $voter = null; // Not logged in
}
?>

<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="#" class="navbar-brand"><b>Voting System</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <?php
            if(isset($_SESSION['student'])){
              echo "
                <li><a href='index.php'>HOME</a></li>
                <li><a href='transaction.php'>TRANSACTION</a></li>
              ";
            } 
          ?>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="user user-menu">
            <a href="">
              <img src="<?php echo (!empty($voter['photo'])) ? 'images/'.$voter['photo'] : 'images/profile.jpg'; ?>" 
                  class="user-image" 
                  alt="User Image">
              <span class="hidden-xs">
                <?php echo (!empty($voter)) ? $voter['firstname'] . ' ' . $voter['lastname'] : 'Guest'; ?>
              </span>
            </a>
          </li>
          <li><a href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a></li>  
        </ul>
      </div>
      <!-- /.navbar-custom-menu -->
    </div>
    <!-- /.container-fluid -->
  </nav>
</header>