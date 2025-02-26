<?php 
include('db_connect.php');
$user_id = $_SESSION['login_id'];
?>

<h2 class="titlepage"> Tenants </h2>

<div class="container-fluid tenantspos">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <b>Active Tenants</b>
            <span class="float:right">
                <a class="btn-sm col-sm-2 float-right newtenantbtn" href="javascript:void(0)" id="new_tenant">
                    <i class="fa fa-plus"></i> New Tenant
                </a>
            </span>
        </div>
        <div class="card-body">
            <table class="table table-condensed table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="">Name</th>
                        <th class="">House Rented</th>
                        <th class="">Monthly Rate</th>
                        <th class="">Outstanding Balance</th>
                        <th class="">Last Payment</th>
                        <th class="">Contract Start</th>
                        <th class="">Contract End</th>   
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $tenant = $conn->query("SELECT t.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, 
                        h.house_no, h.price, t.contract_start, t.contract_end 
                        FROM tenants t 
                        LEFT JOIN houses h ON h.id = t.house_id 
                        WHERE t.status = 1 AND t.user_id = '$user_id' AND h.id IS NOT NULL
                        ORDER BY h.house_no DESC");
                    while($row=$tenant->fetch_assoc()):
                        $start_date = new DateTime($row['contract_start']);
                        $current_date = new DateTime();
                        $interval = $start_date->diff($current_date);
                        $months = $interval->y * 12 + $interval->m;
                        
                        $payable = $row['price'] * $months;
                        
                        $paid = $conn->query("SELECT SUM(amount) as paid FROM payments WHERE tenant_id = ".$row['id']);
                        $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;

                        $last_payment = $conn->query("SELECT * FROM payments WHERE tenant_id = ".$row['id']." ORDER BY UNIX_TIMESTAMP(date_created) DESC LIMIT 1");
                        $last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
                        
                        $outstanding = $payable - $paid;
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td><?php echo ucwords($row['name']) ?></td>
                        <td class="">
                            <p><b><?php echo $row['house_no'] ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo number_format($row['price'],2) ?></b></p>
                        </td>
                        <td class="text-right">
                            <p><b><?php echo number_format($outstanding,2) ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo $last_payment ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo date("M d, Y",strtotime($row['contract_start'])) ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo date("M d, Y",strtotime($row['contract_end'])) ?></b></p> 
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>">View</button>
                            <button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
                            <button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Table Panel -->

<!-- Table Panel -->
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <b>All Tenants</b>
        </div>
        <div class="card-body">
            <table class="table table-condensed table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="">Name</th>
                        <th class="">House Rented</th>
                        <th class="">Monthly Rate</th>
                        <th class="">Outstanding Balance</th>
                        <th class="">Last Payment</th>
                        <th class="">Contract Start</th>
                        <th class="">Contract End</th>   
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $tenant = $conn->query("SELECT t.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, 
                        h.house_no, h.price, t.contract_start, t.contract_end 
                        FROM tenants t 
                        LEFT JOIN houses h ON h.id = t.house_id 
                        WHERE t.user_id = '$user_id'
                        ORDER BY h.house_no DESC ");
                    while($row = $tenant->fetch_assoc()):
                        $start_date = new DateTime($row['contract_start']);
                        $current_date = new DateTime();
                        $interval = $start_date->diff($current_date);
                        $months = $interval->y * 12 + $interval->m;
                        
                        $payable = $row['price'] * $months;
                        
                        $paid = $conn->query("SELECT SUM(amount) as paid FROM payments WHERE tenant_id = ".$row['id']);
                        $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;

                        $last_payment = $conn->query("SELECT * FROM payments WHERE tenant_id = ".$row['id']." ORDER BY UNIX_TIMESTAMP(date_created) DESC LIMIT 1");
                        $last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
                        
                        $outstanding = $payable - $paid;
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td><?php echo ucwords($row['name']) ?></td>
                        <td class="">
                            <p><b><?php echo $row['house_no'] ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo number_format($row['price'],2) ?></b></p>
                        </td>
                        <td class="text-right">
                            <p><b><?php echo number_format($outstanding,2) ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo $last_payment ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo date("M d, Y",strtotime($row['contract_start'])) ?></b></p>
                        </td>
                        <td class="">
                            <p><b><?php echo date("M d, Y",strtotime($row['contract_end'])) ?></b></p> 
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>">View</button>
                            <button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
                            <button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Table Panel -->

	</div>
</div>

<br>

<style>
	td {
		vertical-align: middle !important;
	}
	td p {
		margin: unset
	}
	img {
		max-width: 100px;
		max-height: : 150px;
	}
</style>

<script>
	$(document).ready(function(){
		$('table').dataTable();
	});
	
	$('#new_tenant').click(function(){
		uni_modal("New Tenant","manage_tenant.php","mid-large");
	});

	$('.view_payment').click(function(){
		uni_modal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id'),"large");
	});

	$('.edit_tenant').click(function(){
		uni_modal("Manage Tenant Details","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large");
	});

	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this Tenant?","delete_tenant",[$(this).attr('data-id')]);
	});

	$('#manage-tenant').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');
		$.ajax({
			url:'ajax.php?action=save_tenant',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success');
					setTimeout(function(){
						location.reload();
					},1000);
				}
			}
		});
	});
	
	function delete_tenant($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_tenant',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success');
					setTimeout(function(){
						location.reload();
					},1500);
				}
			}
		});
	}
</script>
