<?php 
include('db_connect.php'); 
$user_id = $_SESSION['login_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(initCharts);

        var chartData = [];
        var lineChart;

        function initCharts() {
            <?php
            $query = "
                SELECT payments.*, tenants.firstname, tenants.lastname
                FROM payments
                JOIN tenants ON payments.tenant_id = tenants.id
                JOIN houses ON tenants.house_id = houses.id
                WHERE houses.user_id = '$user_id'
            ";
            $res = mysqli_query($conn, $query);
            $tenantNames = [];
            while($data = mysqli_fetch_array($res)){
                $date_created = $data['date_created'];
                $amount = $data['amount'];
                $tenant_id = $data['tenant_id'];
                $tenant_name = $data['firstname'] . ' ' . $data['lastname'];
                $dateObj = new DateTime($date_created);
                $year = $dateObj->format('Y');
                $month = $dateObj->format('m') - 1;
                $day = $dateObj->format('d');
                echo "chartData.push([$amount, new Date($year, $month, $day), '$tenant_name']);\n";
                if (!in_array($tenant_name, $tenantNames)) {
                    $tenantNames[] = $tenant_name;
                }
            }
            ?>

            var dropdown = document.getElementById('tenantDropdown');
            var tenantNames = <?php echo json_encode($tenantNames); ?>;
            tenantNames.forEach(function(name) {
                var option = document.createElement('option');
                option.value = name;
                option.text = name;
                dropdown.add(option);
            });

            if (chartData.length > 0) {
                drawLineChart();
            } else {
                document.getElementById('line_chart').innerHTML = '<p>No data available for the line chart.</p>';
            }

        }

        function drawLineChart(tenantName) {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date Created');
            data.addColumn('number', 'Amount');

            var filteredData = chartData.filter(function(row) {
                return tenantName ? row[2] === tenantName : true;
            });

            filteredData.forEach(function(row) {
                data.addRow([row[1], row[0]]);
            });

            if (filteredData.length === 0) {
                document.getElementById('line_chart').innerHTML = '<p>No data available for the selected tenant.</p>';
                return;
            }

            var uniqueDates = [...new Set(filteredData.map(row => row[1]))];

            if (uniqueDates.length === 0) {
                document.getElementById('line_chart').innerHTML = '<p>No data available for the selected tenant.</p>';
                return;
            }

            var minDate = new Date(Math.min.apply(null, uniqueDates));
            var maxDate = new Date(Math.max.apply(null, uniqueDates));

            var dateMargin = 7;
            minDate.setDate(minDate.getDate() - dateMargin);
            maxDate.setDate(maxDate.getDate() + dateMargin);

            var options = {
                title: "Tenant Payment History",
                hAxis: {
                    title: 'Date Created',
                    format: 'MMM dd, yyyy',
                    ticks: uniqueDates,
                    viewWindow: {
                        min: minDate,
                        max: maxDate
                    },
                    gridlines: {
                        color: 'transparent'
                    }
                },
                vAxis: {
                    title: 'Amount',
                    ticks: [0, 2000, 4000, 6000, 8000, 10000, 15000, 20000, 25000, 30000, 35000],
                    viewWindow: {
                        min: 0,
                        max: 35000
                    },
                    gridlines: {
                        color: '#e0e0e0'
                    }
                },
                legend: { position: 'bottom' },
                series: {
                    0: {
                        pointShape: 'circle',
                        pointSize: 10
                    }
                }
            };

            if (!lineChart) {
                lineChart = new google.visualization.LineChart(document.getElementById('line_chart'));
            }

            lineChart.draw(data, options);
        }


        function onTenantChange() {
            var tenantName = document.getElementById('tenantDropdown').value;
            drawLineChart(tenantName);
        }
    </script>

    <script type="text/javascript">
        google.charts.setOnLoadCallback(drawPieChart);
        function drawPieChart() {
            <?php
                $query = "
                    SELECT tenants.firstname, tenants.lastname, SUM(payments.amount) AS total_amount
                    FROM payments
                    JOIN tenants ON payments.tenant_id = tenants.id
                    JOIN houses ON tenants.house_id = houses.id
                    WHERE houses.user_id = '$user_id'
                    GROUP BY payments.tenant_id
                ";
                $res = mysqli_query($conn, $query);
                $pieChartData = [];
                while($data = mysqli_fetch_assoc($res)){
                    $tenant_name = $data['firstname'] . ' ' . $data['lastname'];
                    $pieChartData[] = [$tenant_name, (float)$data['total_amount']];
                }
            ?>

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tenant');
            data.addColumn('number', 'Total Amount');

            data.addRows(<?php echo json_encode($pieChartData); ?>);

            var colors = ['#3366cc', '#dc3912', '#ff9900', '#109618', '#990099'];
            var options = {
                title: 'Total Amount Earned',
                is3D: true,
                colors: colors
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>

</head>

<?php
$lifetimeEarningsQuery = $conn->query("SELECT SUM(amount) as total FROM payments JOIN tenants ON payments.tenant_id = tenants.id JOIN houses ON tenants.house_id = houses.id WHERE houses.user_id = '$user_id'");
$lifetimeEarnings = $lifetimeEarningsQuery->fetch_assoc()['total'];

$previousMonth = date('Y-m', strtotime('-1 month'));
$previousMonthEarningsQuery = $conn->query("SELECT SUM(amount) as total FROM payments JOIN tenants ON payments.tenant_id = tenants.id JOIN houses ON tenants.house_id = houses.id WHERE houses.user_id = '$user_id' AND DATE_FORMAT(date_created, '%Y-%m') = '$previousMonth'");
$previousMonthEarnings = $previousMonthEarningsQuery->fetch_assoc()['total'];

$lastSixMonths = date('Y-m-d', strtotime('-6 months'));
$lastSixMonthsEarningsQuery = $conn->query("SELECT SUM(amount) as total FROM payments JOIN tenants ON payments.tenant_id = tenants.id JOIN houses ON tenants.house_id = houses.id WHERE houses.user_id = '$user_id' AND date_created >= '$lastSixMonths'");
$lastSixMonthsEarnings = $lastSixMonthsEarningsQuery->fetch_assoc()['total'];
?>

<body>
    <h2 class="titlepage">Analytics</h2>
    <div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="width: 100%; height: 100%;">
                        <div class="card-body row justify-content-around align-items-center">
                            <div class="box">
                                <img src="assets/pictures/dollar-sign-solid.svg" id="icon">
                                <h4 id="title4"> Lifetime Earnings </h4>
                                <h3 id="descr"><i class="fa-solid fa-peso-sign"></i> <?php echo number_format((float)$lifetimeEarnings, 2, '.', ','); ?></h3>
                            </div>
                            <div class="box">
                                <img src="assets/pictures/receipt-solid.svg" id="icon">
                                <h4 id="title4"> Previous Month's Earnings</h4>
                                <h3 id="descr"><i class="fa-solid fa-peso-sign"></i> <?php echo number_format((float)$previousMonthEarnings, 2, '.', ','); ?></h3>
                            </div>
                            <div class="box">
                                <img src="assets/pictures/file-invoice-dollar-solid.svg" id="icon">
                                <h4 id="title4"> Last 6 Months' Earnings </h4>
                                <h3 id="descr"><i class="fa-solid fa-peso-sign"></i> <?php echo number_format((float)$lastSixMonthsEarnings, 2, '.', ','); ?></h3>
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
                                </h3>
                            </div>
                            <div class="box">
                                <img src="assets/pictures/house-user-solid.svg" id="icon">
                                <h4 id="title4"> Total Apartments </h4>
                                <h3 id="descr"><?php echo $conn->query("SELECT * FROM houses WHERE user_id = '$user_id'")->num_rows; ?></h3>
                            </div>
                            <div class="box">
                                <img src="assets/pictures/house-solid.svg" id="icon">
                                <h4 id="title4"> Vacant Apartments</h4>
                                <h3 id="descr">
                                    <?php
                                        $result = $conn->query("SELECT * FROM houses WHERE occupancy_status = 'Vacant' AND user_id = '$user_id'");
                                        echo $result->num_rows;
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="width: 100%; height: 100%;">
                        <div class="card-header">
                            <b>Money Earned</b>
                        </div>
                        <div class="card-body">
                            <div id="piechart_3d" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <br>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <b>Timeline</b>
            </div>
            <div class="card-body" style="width: 100%;">
                <select id="tenantDropdown" onchange="onTenantChange()">
                    <option value="">All Tenants</option>
                </select>
                <div id="line_chart" style="width: 100%; height: 500px"></div>
            </div>
        </div>
    </div>
</body>
<style>
    td {
        vertical-align: middle !important;
    }

    .box {
        padding: 10px 10px 20px 10px; 
    }

</style>
</html>
