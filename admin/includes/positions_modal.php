<!-- Add Position -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Add New Position</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="positions_add.php">
                    <div class="form-group">
                        <label for="session_id" class="col-sm-3 control-label">Election Name</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="session_id" name="session_id" required>
                                <option value="" selected disabled>Select Election</option>
                                <?php
                                    // Fetch sessions from the database
                                    $sql = "SELECT id, title FROM sessions ORDER BY created_at DESC";
                                    $query = $conn->query($sql);
                                    while($row = $query->fetch_assoc()){
                                        echo "<option value='".$row['id']."'>".$row['title']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Position Name</label>

                        <div class="col-sm-9">
                            <!-- <input type="text" class="form-control" id="description" name="description" required> -->
                            <select type="text" name="description" id="description" class="form-control" required>
                            <option value=" " selected disabled>Select Position</option>
                            <option value="President">President</option>
                            <option value="Vice President">Vice President</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Treasurer">Treasurer</option>
                            <option value="Auditor">Auditor</option>
                            <option value="PIO">PIO</option>
                            <option value="Business Managers">Business Managers</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_vote" class="col-sm-3 control-label">Maximum Vote</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="max_vote" name="max_vote" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="priority" class="col-sm-3 control-label">Priority</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="priority" name="priority" required>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
                        </form>
                    </div>
                    
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div class="modal fade" id="Addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Add New Position</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="add_positions.php">
                    <!-- Position Name Input -->
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Position Name</label>
                        <div class="col-sm-9">
                            <!-- Input with Datalist -->
                            <input 
                                type="text" 
                                name="description" 
                                id="description" 
                                class="form-control col-sm-3" 
                                list="positionOptions" 
                                placeholder="Type or select a position" 
                                required>
                            <!-- Predefined Options -->
                            <datalist id="positionOptions">
                                <option value="President">
                                <option value="Vice President">
                                <option value="Secretary">
                                <option value="Treasurer">
                                <option value="Auditor">
                                <option value="PIO">
                                <option value="Business Managers">
                            </datalist>
                        </div>
                    </div>

                    <!-- Maximum Vote -->
                    <div class="form-group">
                        <label for="max_vote" class="col-sm-3 control-label">Maximum Vote</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="max_vote" name="max_vote" required>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="form-group">
                        <label for="priority" class="col-sm-3 control-label">Priority</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="priority" name="priority" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                        <i class="fa fa-close"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary btn-flat" name="add">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
                </form>
            </div>     
        </div>
    </div>
</div>



<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Position</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="positions_edit.php">
                <input type="hidden" class="id" name="id">
                <div class="form-group">
                    <label for="edit_description" class="col-sm-3 control-label">Description</label>

                    <div class="col-sm-9">
                      <!-- <input type="text" class="form-control" id="edit_description" name="description"> -->
                        <select type="text" name="description" id="description" class="form-control" required>
                            <option value="President">President</option>
                            <option value="Vice President">Vice President</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Treasurer">Treasurer</option>
                            <option value="Auditor">Auditor</option>
                            <option value="PIO">PIO</option>
                            <option value="Business Managers">Business Managers</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_max_vote" class="col-sm-3 control-label">Maximum Vote</label>

                    <div class="col-sm-9">
                      <input type="number" class="form-control" id="edit_max_vote" name="max_vote">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Deleting...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="positions_delete.php">
                <input type="hidden" class="id" name="id">
                <div class="text-center">
                    <p>DELETE POSITION</p>
                    <h2 class="bold description"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>



     