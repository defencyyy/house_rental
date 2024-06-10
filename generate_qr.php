<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payeeUserId = $_POST['payeeUserId'];
    $payeeName = $_POST['payeeName'];
    $amount = $_POST['amount'];

    $qrCode = GCashLib::generate(
        payeeUserId: $payeeUserId,
        payeeName: $payeeName,
        amount: $amount
    );

    echo '<div class="text-center">';
    echo '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($qrCode) . '&amp;size=200x200" alt="" title="" />';
    echo '<p>Scan this QR code to pay ' . $amount . ' PHP to ' . $payeeName . '</p>';
    echo '</div>';
}
?>
