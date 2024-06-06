<?php
require 'vendor/autoload.php';
include('db_connect.php');
use Vocolboy\PromptpayGenerator\GCashLib;
?>

<h2 class="titlepage"> Payments </h2>

<div class="container-fluid paymentspos">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        <div class="row">
            <!-- FORM Panel -->
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Payments</b>
                        <span class="float:right">
                            <a class="btn-sm col-sm-2 float-right newtenantbtn" href="javascript:void(0)" id="new_invoice">
                                <i class="fa fa-plus"></i> New Entry
                            </a>
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Date</th>
                                    <th class="">Tenant</th>
                                    <th class="">Invoice</th>
                                    <th class="">Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
																$user_id = $_SESSION['login_id'];
                                $i = 1;
                                $invoices = $conn->query("SELECT p.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name 
                                                          FROM payments p 
                                                          INNER JOIN tenants t ON t.id = p.tenant_id 
                                                          WHERE t.status = 1 AND p.user_id = '$user_id' 
                                                          ORDER BY DATE(p.date_created) DESC ");
                                while ($row = $invoices->fetch_assoc()):
                                    $qrCode = GCashLib::generate($row['gcash_id'], $row['name'], $row['amount']); // Generate QR Code
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['date_created'])) ?></td>
                                    <td class="">
                                        <p><b><?php echo ucwords($row['name']) ?></b></p>
                                    </td>
                                    <td class="">
                                        <p><b><?php echo ucwords($row['invoice']) ?></b></p>
                                    </td>
                                    <td class="text-right">
                                        <p><b><?php echo number_format($row['amount'], 2) ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary edit_invoice" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger delete_invoice" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                        <button class="btn btn-sm btn-outline-success generate_qr" type="button" data-qr="<?php echo $qrCode ?>">Generate QR</button>
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
</div>
<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset
    }
    img {
        max-width: 100px;
        max-height: :150px;
    }
</style>
<script>
    $(document).ready(function() {
        $('table').dataTable()
    })

    $('#new_invoice').click(function() {
        uni_modal("New invoice", "manage_payment.php", "mid-large")
    })
    $('.edit_invoice').click(function() {
        uni_modal("Manage invoice Details", "manage_payment.php?id=" + $(this).attr('data-id'), "mid-large")
    })
    $('.delete_invoice').click(function() {
        _conf("Are you sure to delete this invoice?", "delete_invoice", [$(this).attr('data-id')])
    })
    $('.generate_qr').click(function() {
        var qrCode = $(this).attr('data-qr');
        alert("QR Code: " + qrCode);
    })
    function delete_invoice(id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_payment',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>
