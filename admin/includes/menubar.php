<aside class="main-sidebar main-header-1">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo (!empty($user['photo'])) ? '../images/'.$user['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $user['firstname'].' '.$user['lastname']; ?></p>
        <a><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu main-header-1" data-widget="tree">
      <li class="header">REPORTS</li>
      <li class=""><a href="home.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
      <li class=""><a href="election_status.php"><i class="glyphicon glyphicon-lock"></i> <span>Election Status</span></a></li>
      <li class="header">MANAGE</li>
      <li class=""><a href="sessions.php"><i class="glyphicon glyphicon-lock"></i> <span>Elections</span></a></li>
      <li class=""><a href="positions.php"><i class="fa fa-tasks"></i> <span>Positions</span></a></li>
      <li class=""><a href="candidates.php"><i class="fa fa-black-tie"></i> <span>Candidates</span></a></li>
      <li class=""><a href="voters.php"><i class="fa fa-users"></i> <span>Voters</span></a></li>
      <li class=""><a href="sam_ballot.php"><i class="fa fa-file-text"></i> <span>Ballots</span></a></li>
      <!-- <li class="header">SETTINGS</li> -->
      <!-- <li class=""><a href="#config" data-toggle="modal"><i class="fa fa-cog"></i> <span>Election Title</span></a></li> -->
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<?php include 'config_modal.php'; ?>