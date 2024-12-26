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
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Election</li>
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
							".(is_array($_SESSION['error']) ? implode('<br>', $_SESSION['error']) : $_SESSION['error'])."
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
						<div class="box-body table-responsive">
							<table id="example1" class="table table-bordered " style="overflow-y:auto ; max-width:100%;"> 
								<thead >
									<tr class="bg-dark">
									<th scope="col">Title</th>
									<th scope="col">Course</th>
									<th scope="col">Status</th>
									<th scope="col">Preview</th>
									<th scope="col">Vote Now</th>
									</tr>
								</thead>
								<tbody>
									<?php
										// Fetch sessions data from database
										$sql = "SELECT * FROM sessions";
										$query = $conn->query($sql);
										while ($row = $query->fetch_assoc()) {
											// Determine the election status
											$current_date = date('Y-m-d');
											$start_date = $row['start_date'];
											$end_date = $row['end_date'];
											$status_badge = "<span class='btn btn-danger btn-md delete btn-flat'>Closed</span>"; // Default to Closed

											if ($current_date >= $start_date && $current_date <= $end_date) {
												$status_badge = "<span class='btn btn-success btn-md edit btn-flat'>Open</span>"; // Change to Open if applicable
											}
										
											// Generate table rows
											echo "
												<tr>
													<td>{$row['title']}</td>
													<td>{$row['course']}</td>
													<td>
													{$status_badge}
													</td>
													
													<td>
														<a href='#view' 
															data-toggle='modal'
														   data-session-id='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' 
														   data-voter-id='" . htmlspecialchars($_SESSION['voter_id'], ENT_QUOTES, 'UTF-8') . "' 
														   class='btn btn-primary btn-sm btn-flat view-ballot-btn'>
														   <i class='fa fa-eye'></i> View Ballot
														</a>
													</td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php include 'includes/ballot_modal.php'; ?>
</body>
</html>
<script>
   $(document).on('click', '.view-ballot-btn', function () {
	console.log('View Ballot button clicked!'); 
    const sessionId = $(this).data('session-id');
    const voterId = $(this).data('voter-id'); // Dynamically pass voter ID
    $('#modal-content').html('<p class="text-center">Loading...</p>'); // Show loading spinner

    $.ajax({
        url: 'fetch_votes.php',
        method: 'GET',
        data: { session_id: sessionId, voter_id: voterId },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                let content = '<h4>' + response.votes[0].election_title + '</h4>';
                content += '<table class="table table-bordered">';
                content += '<thead><tr><th>Position</th><th>Candidate</th></tr></thead><tbody>';
                response.votes.forEach(vote => {
                    content += `<tr>
                                  <td>${vote.position_name}</td>
                                  <td>${vote.candidate_name}</td>
                                </tr>`;
                });
                content += '</tbody></table>';
                $('#modal-content').html(content);
            } else {
                $('#modal-content').html('<p class="text-center text-danger">' + response.message + '</p>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#modal-content').html(`<p class="text-center text-danger">
                Error: ${textStatus}, ${errorThrown}
            </p>`);
            console.error('AJAX Error: ', jqXHR.responseText);
        }
    });
});
</script>