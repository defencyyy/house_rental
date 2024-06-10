<?php 
include('db_connect.php');
session_start(); 
$user_id = $_SESSION['login_id'];

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM tenants WHERE id = " . $_GET['id'] . " AND user_id = '$user_id'");
    if ($qry->num_rows > 0) {
        $tenant = $qry->fetch_assoc();
        foreach($tenant as $k => $v){
            $$k = $v;
        }
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-tenant">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <!-- Hidden input field for registration date -->
        <input type="hidden" name="date_in" id="date_in" value="<?php echo isset($date_in) ? date("Y-m-d", strtotime($date_in)) : '' ?>">
        <div class="row form-group">
            <div class="col-md-4">
                <label for="" class="control-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo isset($lastname) ? $lastname :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">First Name</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo isset($firstname) ? $firstname :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Middle Name</label>
                <input type="text" class="form-control" name="middlename" value="<?php echo isset($middlename) ? $middlename :'' ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">Email Address</label>
                <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email :'' ?>">
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Contact Number</label>
                <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">House to Rent</label>
                <select name="house_id" id="" class="custom-select select2">
                    <option value=""></option>
                    <?php 
                    $query = "SELECT * FROM houses 
                                WHERE id NOT IN (SELECT house_id FROM tenants 
                                WHERE status = 1 AND user_id = '$user_id') 
                                AND user_id = '$user_id' 
                                AND occupancy_status != 'Maintenance' " 
                    . (isset($house_id) ? " OR id = $house_id" : "");
          

                    $house = $conn->query($query);

                    if ($house === false) {
                        echo "Error: " . $conn->error;
                    } else {
                        if ($house->num_rows > 0) {
                            while($row = $house->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['house_no'] ?></option>
                    <?php 
                            endwhile;
                        } else {
                            echo "<option value=''>No houses available</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">Contract Start</label>
                <input type="date" class="form-control" name="contract_start" value="<?php echo isset($contract_start) ? $contract_start : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Contract End</label>
                <input type="date" class="form-control" name="contract_end" value="<?php echo isset($contract_end) ? $contract_end : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Date In</label>
                <input type="date" class="form-control" name="date_in" value="<?php echo isset($date_in) ? $date_in : date('Y-m-d'); ?>" required>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        if ($('input[name="id"]').val() == '') {
            var today = new Date().toISOString().split('T')[0];
            $('#date_in').val(today);
        }
    });

    $('#manage-tenant').submit(function(e){
        e.preventDefault();
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'ajax.php?action=save_tenant',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    });
</script>