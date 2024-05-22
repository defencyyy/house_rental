<?php include('db_connect.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(initCharts);

        var chartData = [];
        var lineChart;

        function initCharts() {
            // Fetch initial data from PHP
            <?php
            $query = "
                SELECT payments.*, tenants.firstname, tenants.lastname
                FROM payments
                JOIN tenants ON payments.tenant_id = tenants.id
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
                $month = $dateObj->format('m') - 1; // months are zero-indexed in JavaScript
                $day = $dateObj->format('d');
                echo "chartData.push([$amount, new Date($year, $month, $day), '$tenant_name']);\n";
                if (!in_array($tenant_name, $tenantNames)) {
                    $tenantNames[] = $tenant_name;
                }
            }
            ?>

            // Populate dropdown menu
            var dropdown = document.getElementById('tenantDropdown');
            var tenantNames = <?php echo json_encode($tenantNames); ?>;
            tenantNames.forEach(function(name) {
                var option = document.createElement('option');
                option.value = name;
                option.text = name;
                dropdown.add(option);
            });

            // Draw initial line chart
            drawLineChart();
        }

        function drawLineChart(tenantName) {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date Created');
            data.addColumn('number', 'Amount');

            var filteredData = chartData.filter(function(row) {
                return row[2] == tenantName;
            });

            filteredData.forEach(function(row) {
                data.addRow([row[1], row[0]]);
            });

            var options = {
                title: "Tenant Payment History",
                hAxis: {
                    title: 'Date Created',
                    format: 'MMM dd, yyyy'
                },
                vAxis: {
                    title: 'Amount'
                },
                legend: { position: 'bottom' }
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
            // Fetch data from PHP
            <?php
                $query = "
                    SELECT tenants.firstname, tenants.lastname, SUM(payments.amount) AS total_amount
                    FROM payments
                    JOIN tenants ON payments.tenant_id = tenants.id
                    GROUP BY payments.tenant_id
                ";
                $res = mysqli_query($conn, $query);
                $pieChartData = [];
                while($data = mysqli_fetch_assoc($res)){
                    $tenant_name = $data['firstname'] . ' ' . $data['lastname'];
                    $pieChartData[] = [$tenant_name, (float)$data['total_amount']];
                }
            ?>

            // Create pie chart data
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tenant');
            data.addColumn('number', 'Total Amount');

            data.addRows(<?php echo json_encode($pieChartData); ?>);

            // Set color options
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
<body>
    <h2 class="titlepage">Analytics</h2>
    
    <div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="width: 100%; height: 100%;">
                        <div class="card-body row justify-content-around align-items-center">
                            <div class = "box">
                                <img src="assets/pictures/available.png" id="icon">
                                <h4 id = "title4"> Total Amount Earned </h4>
                                <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
                            </div>
                            <div class = "box">
                            <img src="assets/pictures/available.png" id="icon">
                                <h4 id = "title4"> Total Apartments </h4>
                                <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
                            </div>
                            <div class = "box">
                            <img src="assets/pictures/available.png" id="icon">
                                <h4 id = "title4"> Total Apartments </h4>
                                <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
                            </div>
                            <div class = "box">
                            <img src="assets/pictures/available.png" id="icon">
                                <h4 id = "title4"> Total Apartments </h4>
                                <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
                            </div>
                            <div class = "box">
                            <img src="assets/pictures/available.png" id="icon">
                                <h4 id = "title4"> Total Apartments </h4>
                                <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
                            </div>
                            <div class = "box">
                            <img src="assets/pictures/available.png" id="icon">
                                <h4 id = "title4"> Total Apartments </h4>
                                <h3 id = "descr"> <?php echo $conn->query("SELECT * FROM houses")->num_rows ?> </h3>
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
            <div class="card-body">
                <select id="tenantDropdown" onchange="onTenantChange()">
                    <option value="">Select Tenant</option>
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
