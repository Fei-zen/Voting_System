
<!-- Add New Candidates -->
<div class="modal fade" id="Addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Add New Candidate</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="add_candidates.php" enctype="multipart/form-data">
                    <!-- Election Name -->
                    <div class="form-group">
                        <label for="session_id" class="col-sm-3 control-label">Election Name</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="session_id" name="session_id" required>
                                <option value="" selected disabled>- Select -</option>
                                <?php
                                    $sql = "SELECT id, title FROM sessions ORDER BY created_at DESC";
                                    $query = $conn->query($sql);
                                    while ($row = $query->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Position -->
                    <div class="form-group">
                        <label for="position" class="col-sm-3 control-label">Position</label>
                        <div class="col-sm-9">
                            <select class="form-control position-select" name="position" required>
                                <option value="" selected disabled>- Select -</option>
                                <?php
                                    $sql = "SELECT * FROM positions";
                                    $query = $conn->query($sql);
                                    while ($row = $query->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['description'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Candidate Name with Autocomplete -->
                    <div class="form-group">
                        <label for="candidate_name" class="col-sm-3 control-label">Candidate Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="candidate_name" name="candidate_name" placeholder="Search and select candidate..." required>
                            <input type="hidden" id="voter_id" name="voter_id"> <!-- Hidden Field -->
                        </div>
                    </div>

                    <!-- Photo -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" class="photo-input" name="photo">
                        </div>
                    </div>

                    <!-- Platform -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Platform</label>
                        <div class="col-sm-9">
                            <textarea class="form-control platform-textarea" name="platform" rows="7"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<!-- Scripts -->
<!-- jQuery (3.6.0) -->
<!-- jQuery and jQuery UI -->
<!-- jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">

<!-- Autocomplete Script -->
<script>
    $(document).ready(function () {
    $("#candidate_name").autocomplete({
        source: "search_voters.php", // Backend returns JSON data
        minLength: 2,
        appendTo: "#Addnew .modal-body", // Append dropdown to modal body
        open: function () {
            // Reference input and dropdown elements
            var $input = $("#candidate_name");
            var $autocomplete = $(".ui-autocomplete");

            // Position dropdown manually
            var inputPosition = $input.position("#candidate_name"); // Position relative to modal body
            var inputHeight = $input.outerHeight();

            $autocomplete.css({
                top: inputPosition.top + inputHeight + "px", // Position below input
                left: inputPosition.left + "px", // Align horizontally
                width: $input.outerWidth() + "px" // Match input width
            });
        },
        focus: function (event, ui) {
            $("#candidate_name").val(ui.item.label);
            return false;
        },
        select: function (event, ui) {
            $("#candidate_name").val(ui.item.label);
            $("#voter_id").val(ui.item.id);
            return false;
        }
    });
});


</script>

<!-- CSS for Autocomplete Dropdown -->
<style>
    .ui-autocomplete {
        z-index: 1050 !important; /* Ensure it's on top of modal */
        max-height: 150px; /* Restrict dropdown height */
        overflow-y: auto; /* Add scroll if needed */
        align-content: center;
        background-color: white;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .ui-autocomplete li {
        padding: 5px;
        cursor: pointer;
    }

    .ui-autocomplete li:hover {
        background-color: #f0f0f0;
    }
</style>