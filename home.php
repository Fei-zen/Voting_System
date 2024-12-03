<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">



	<?php include 'includes/navbar.php'; ?>
	 
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
			<h1>
				Election List
			</h1>
			<ol class="breadcrumb">
				<!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
				<li class="active">Election</li>
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
						<th>Title</th>
						<th>Course</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Created At</th>
						<th>Vote Now</th>
						</thead>
						<tbody>
							<?php
								// Fetch sessions data from database
								$sql = "SELECT * FROM sessions";
								$query = $conn->query($sql);
								while ($row = $query->fetch_assoc()) {
									echo "
										<tr>
											<td>{$row['title']}</td>
											<td>{$row['course']}</td>
											<td>{$row['start_date']}</td>
											<td>{$row['end_date']}</td>
											<td>{$row['created_at']}</td>
											<td>
												<a href='vote_logic.php?session_id={$row['id']}' class='btn btn-primary btn-sm vote-btn'>Vote Now</a>
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
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>