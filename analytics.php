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
            $query = "SELECT * FROM payments";
            $res = mysqli_query($conn, $query);
            $tenantIds = [];
            while($data = mysqli_fetch_array($res)){
                $date_created = $data['date_created'];
                $amount = $data['amount'];
                $tenant_id = $data['tenant_id'];
                $dateObj = new DateTime($date_created);
                $year = $dateObj->format('Y');
                $month = $dateObj->format('m') - 1; // months are zero-indexed in JavaScript
                $day = $dateObj->format('d');
                echo "chartData.push([$amount, new Date($year, $month, $day), $tenant_id]);\n";
                if (!in_array($tenant_id, $tenantIds)) {
                    $tenantIds[] = $tenant_id;
                }
            }
            ?>

            // Populate dropdown menu
            var dropdown = document.getElementById('tenantDropdown');
            var tenantIds = <?php echo json_encode($tenantIds); ?>;
            tenantIds.forEach(function(id) {
                var option = document.createElement('option');
                option.value = id;
                option.text = 'Tenant ' + id;
                dropdown.add(option);
            });

            // Draw initial line chart
            drawLineChart();
        }

        function drawLineChart(tenantId) {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date Created');
            data.addColumn('number', 'Amount');

            var filteredData = chartData.filter(function(row) {
                return row[2] == tenantId;
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
            var tenantId = document.getElementById('tenantDropdown').value;
            drawLineChart(tenantId);
        }
    </script>

		<script type="text/javascript">
				google.charts.setOnLoadCallback(drawPieChart);
				function drawPieChart() {
						// Fetch data from PHP
						<?php
								$query = "SELECT tenant_id, SUM(amount) AS total_amount FROM payments GROUP BY tenant_id";
								$res = mysqli_query($conn, $query);
								$pieChartData = [];
								while($data = mysqli_fetch_assoc($res)){
										$pieChartData[] = [$data['tenant_id'], (float)$data['total_amount']];
								}
						?>

						// Create pie chart data
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'Tenant ID');
						data.addColumn('number', 'Total Amount');

						data.addRows(<?php echo json_encode($pieChartData); ?>);

						// Set color options based on tenant_id
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
<div id="piechart_3d" style="width: 900px; height: 500px;"></div>
<select id="tenantDropdown" onchange="onTenantChange()">
    <option value="">Select a tenant</option>
</select>
<div id="line_chart" style="width: 900px; height: 500px"></div>
</body>
<style>
    td {
        vertical-align: middle !important;
    }
</style>
</html>
