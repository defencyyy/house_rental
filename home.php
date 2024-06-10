<?php 
include('db_connect.php');
$user_id = $_SESSION['login_id'];
?>

<div class="wrapper">
    <div class="content">
        <h1 id="title"> Welcome, <?php echo $_SESSION['login_name']."!" ?> </h1>
        <p id="description"> See the summary of your apartments. </p>
    </div>
    <img src="assets/pictures/homepagepic.png" id="hppic">
</div>

<div id="stats">
    <h2 id="title3"> Quick Stats </h2>
    <div class="quickstats">
        <div class="box">
            <img src="assets/pictures/total.png" id="icon">
            <h4 id="title4"> Total Apartments </h4>
            <h3 id="descr"> <?php echo $conn->query("SELECT * FROM houses WHERE user_id = '$user_id'")->num_rows ?> </h3>
        </div>
        <div class="box">
            <img src="assets/pictures/person-svgrepo-com.svg" id="icon">
            <h4 id="title4"> Active Tenants </h4>
            <h3 id="descr">
                <?php
                    $tenant = $conn->query("SELECT COUNT(t.id) as active_tenants FROM tenants t INNER JOIN houses h ON h.id = t.house_id WHERE t.status = 1 AND h.user_id = '$user_id'");
                    $activeTenants = $tenant->fetch_assoc()['active_tenants'];
                    echo $activeTenants;
                ?>
            </h3>
        </div>
        <div class="box">
            <img src="assets/pictures/available.png" id="icon">
            <h4 id="title4"> Available Apartments</h4>
            <h3 id="descr">
                <?php
                    $result = $conn->query("SELECT * FROM houses WHERE occupancy_status = 'Vacant' AND user_id = '$user_id'");
                    echo $result->num_rows;
                ?>
            </h3>
        </div>
    </div>
</div>
