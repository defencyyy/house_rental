<?php include('db_connect.php');?>

<div class = "wrapper">
    <div class = "content">
        <h1 id = "title"> Welcome, <?php echo $_SESSION['login_name']."!"  ?> </h1>
        <p id = "description"> See the summary of your apartments. </p>
    </div>
    <img src = "assets\pictures\homepagepic.png" id = hppic>
</div>

<div id = "stats">
    <h2 id = "title3"> Quick Stats </h2>
    <div class = "quickstats">
        <div class = "box">
            <img src = "assets\pictures\total.png" id = "icon">
            <h4 id = "title4"> Total Apartments </h4>
            <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
        </div>
        <div class="box">
            <img src="assets/pictures/available.png" id="icon">
            <h4 id="title4">Available Apartments</h4>
            <h3 id="descr">
                <?php
                    $result = $conn->query("SELECT * FROM houses WHERE occupancy_status = 'Vacant'");
                    echo $result->num_rows;
                ?>
            </h3>
        </div>
        <div class="box">
            <img src="assets/pictures/pending.png" id="icon">
            <h4 id="title4">Pending Payments</h4>
            <h3 id="descr">
                <?php
                    $tenant = $conn->query("SELECT t.*, h.price FROM tenants t INNER JOIN houses h ON h.id = t.house_id WHERE t.status = 1");
                    $pendingPayments = 0;
                    while($row = $tenant->fetch_assoc()) {
                        $months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
                        $months = floor(($months) / (30*60*60*24));
                        $payable = $row['price'] * $months;
                        $paid = $conn->query("SELECT SUM(amount) as paid FROM payments WHERE tenant_id = ".$row['id']);
                        $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
                        $outstanding = $payable - $paid;
                        if ($outstanding < 0) {
                            $pendingPayments++;
                        }
                    }
                    echo $pendingPayments;
                ?>
            </h3>
        </div>
    </div>
</div>

<div class = "data-card">
    <div class = "card-data">
        <p class = "title-card">Occupany Rate</p>
        <p class = "data-analytic"> data </p>
        <p class = "data-analytic-2"> data </p>
    </div>
    <div class = "card-data">
        <p class = "title-card">Last Month's Totals</p>
        <p class = "data-analytic"> data </p>
        <p class = "data-analytic-2"> data </p>
    </div>
    <div class = "card-data">
        <p class = "title-card">Previous Six (6) Month's Totals</p>
        <p class = "data-analytic"> data </p>
        <p class = "data-analytic-2"> data </p>
    </div>
</div>
