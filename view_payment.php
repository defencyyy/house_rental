<?php include 'db_connect.php' ?>

<?php 
$id = isset($_GET['id']) ? $_GET['id'] : '';
$tenants =$conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = $id ");
$tenant = $tenants->fetch_assoc();
if($tenant){
	foreach($tenant as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
	$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
	$months = floor(($months) / (30*60*60*24));
	$payable = $price * $months;
	$paid_result = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =$id");
	$paid = $paid_result->num_rows > 0 ? $paid_result->fetch_array()['paid'] : 0;
	$last_payment_result = $conn->query("SELECT * FROM payments where tenant_id =$id order by unix_timestamp(date_created) desc limit 1");
	$last_payment = $last_payment_result->num_rows > 0 ? date("M d, Y",strtotime($last_payment_result->fetch_array()['date_created'])) : 'N/A';
	$outstanding = $payable - $paid;
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-4">
				<div id="details">
					<large><b>Details</b></large>
					<hr>
					<p>Tenant: <b><?php echo isset($name) ? ucwords($name) : 'N/A' ?></b></p>
					<p>Monthly Rental Rate: <b><?php echo isset($price) ? number_format($price,2) : '0.00' ?></b></p>
					<p>Outstanding Balance: <b><?php echo isset($outstanding) ? number_format($outstanding,2) : '0.00' ?></b></p>
					<p>Total Paid: <b><?php echo isset($paid) ? number_format($paid,2) : '0.00' ?></b></p>
					<p>Rent Started: <b><?php echo isset($date_in) ? date("M d, Y",strtotime($date_in)) : 'N/A' ?></b></p>
					<p>Payable Months: <b><?php echo isset($months) ? $months : '0' ?></b></p>
				</div>
			</div>
			<div class="col-md-8">
				<large><b>Payment List</b></large>
					<hr>
				<table class="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>Invoice</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($id)){
							$payments = $conn->query("SELECT * FROM payments WHERE tenant_id = $id");
							if($payments->num_rows > 0):
								while($row=$payments->fetch_assoc()):
						?>
									<tr>
										<td><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
										<td><?php echo $row['invoice'] ?></td>
										<td class='text-right'><?php echo number_format($row['amount'],2) ?></td>
									</tr>
						<?php 
								endwhile;
							else:
						?>
								<tr><td colspan="3" class="text-center">No payment record found.</td></tr>
						<?php 
							endif;
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	#details p {
		margin: unset;
		padding: unset;
		line-height: 1.3em;
	}
	td, th{
		padding: 3px !important;
	}
</style>
