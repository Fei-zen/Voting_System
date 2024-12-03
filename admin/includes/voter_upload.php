<!-- Add -->
<div class="modal fade" id="uploadnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add New Voters</b></h4>
            </div>
            <div class="modal-body">
				<h1>Upload Voter Information</h1><br>
				<form action="upload_voters.php" method="POST" enctype="multipart/form-data"><br>
					<label for="file">Upload CSV File:</label><br>
					<input type="file" name="file" id="file" accept=".csv" required><br>
					<button type="submit" class="btn btn-primary btn-flat" name="upload">Upload</button><br>
				</form>
            </div>
        </div>
    </div>
</div>



     