<!-- Session Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add New Election</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="session_add.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">Title:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"  id="title" name="title" required>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="course" class="col-sm-3 control-label">Course:</label>
                        <div class="col-sm-9">
                            <select id="course" class="form-control" name="course" required>
                                <option value=" " selected disabled>Select Course</option>
                                <option value="BSCS">BSCS</option>
                                <option value="BSIS">BSIS</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BTVTEd">BTVTEd</option>
                                <option value="CICT">CICT</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="start_date"  class="col-sm-3 control-label">Start Date:</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="end_date" class="col-sm-3 control-label">End Date:</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required><br><br>
                            <button type="submit" class="btn btn-primary btn-flat">Create Session</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Session Edit -->
<div class="modal fade" id="edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><b>Edit Session</b></h4>
      </div>
      <form action="session_edit.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" class="id">
          <div class="form-group">
            <label for="edit_title">Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edit_course">Course</label>
            <select name="course" id="edit_course" class="form-control" required>
              <option value=" " selected>Select</option>
              <option value="BSCS">BSCS</option>
              <option value="BSIS">BSIS</option>
              <option value="BSIT">BSIT</option>
              <option value="BTVTEd">BTVTEd</option>
              <option value="CICT">CICT</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_start_date">Start Date</label>
            <input type="datetime-local" name="start_date" id="edit_start_date" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edit_end_date">End Date</label>
            <input type="datetime-local" name="end_date" id="edit_end_date" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Session Delete -->
<div class="modal fade" id="delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><b>Delete Session</b></h4>
      </div>
      <form action="session_delete.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" class="id">
          <p>Are you sure you want to delete this session?</p>
          <h4 class="session_title text-center"></h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

