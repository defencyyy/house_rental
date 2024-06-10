<?php
include('db_connect.php');
session_start(); 
$user_id = $_SESSION['login_id'];
$id = isset($_GET['id']) ? $_GET['id'] : '';
$tenants = $conn->query("SELECT t.*, CONCAT(t.lastname,', ',t.firstname,' ',t.middlename) AS name, h.house_no, h.price
                         FROM tenants t 
                         INNER JOIN houses h ON h.id = t.house_id 
                         WHERE t.id = $id AND t.user_id = $user_id");
$tenant = $tenants->fetch_assoc();
foreach($tenant as $k => $v){
    if(!is_numeric($k)){
        $$k = $v;
    }
}

$start_date = new DateTime($contract_start);
$current_date = new DateTime();
$interval = $start_date->diff($current_date);
$months = $interval->y * 12 + $interval->m;

$payable = $price * $months;

$paid_result = $conn->query("SELECT SUM(amount) as paid FROM payments WHERE tenant_id = $id");
$paid = $paid_result->num_rows > 0 ? $paid_result->fetch_array()['paid'] : 0;

$last_payment_result = $conn->query("SELECT * FROM payments WHERE tenant_id = $id ORDER BY UNIX_TIMESTAMP(date_created) DESC LIMIT 1");
$last_payment = $last_payment_result->num_rows > 0 ? date("M d, Y",strtotime($last_payment_result->fetch_array()['date_created'])) : 'N/A';

$outstanding = $payable - $paid;

$name = $tenant['name'];
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
