
<?php
		require 'partials/security.php';
    require 'partials/header.php';
    require 'model/Database.php';
?>

<?php
$db = new Database();

/* =========================
   AREA CHART – MONTHLY SALES
   ========================= */
$year = date('Y');

$stmt = $db->conn->prepare("
    SELECT 
        MONTH(TransacDate) AS month_no,
        MONTHNAME(TransacDate) AS month_name,
        SUM(Amount) AS total
    FROM transaction_tbl
    WHERE Status='Paid'
      AND YEAR(TransacDate)=:year
    GROUP BY MONTH(TransacDate)
    ORDER BY MONTH(TransacDate)
");
$stmt->execute(['year' => $year]);

$months = [];
$monthlyTotals = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $months[] = $row['month_name'];
    $monthlyTotals[] = (float)$row['total'];
}

/* =========================
   PIE CHART – YEARLY SALES
   ========================= */
$stmt2 = $db->conn->query("
    SELECT 
        YEAR(TransacDate) AS year,
        SUM(Amount) AS total
    FROM transaction_tbl
    WHERE Status='Paid'
    GROUP BY YEAR(TransacDate)
    ORDER BY year
");

$years = [];
$yearTotals = [];

while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $years[] = $row['year'];
    $yearTotals[] = (float)$row['total'];
}
?>


  <!-- Page Wrapper -->
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>

    <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
		<!-- Main Content -->
		<div id="content">

			<!-- Topbar -->
			<?php
					require 'partials/nav.php';
			?>

			<!-- Begin Page Content -->
	<div class="container-fluid">

		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-danger">Chart Dashboard</h1>
				<!-- <a href="/billing">
					<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modelProduct"><strong>Billing</strong></button>
				</a> -->
		</div>

		<!-- Content Row -->
		<div class="row">

			<!-- Area Chart -->
			<div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-column flex-md-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">Monthly Sales (<?= date('Y') ?>)</h6>
            
            <div class="dropdown-no-arrow">
                <select id="yearSelector" class="form-control form-control-sm shadow-sm">
                    <?php
                    $stmt = $db->conn->query("
                        SELECT DISTINCT YEAR(TransacDate) AS year
                        FROM transaction_tbl
                        WHERE TransacDate IS NOT NULL
                        ORDER BY year DESC
                    ");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($row['year'] == date('Y')) ? 'selected' : '';
                        echo "<option value='{$row['year']}' $selected>{$row['year']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="chart-area">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>
</div>

			<!-- Pie Chart -->
			<div class="col-xl-4 col-lg-5">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary"> Sales by Year</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-pie pt-4 pb-2">
							<canvas id="myPieChart"></canvas>
						</div>
					</div>
				</div>
			</div>
			
		</div>

	</div>




<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
 



		<!-- End of Main Content -->
<?php  require 'partials/footer.php'; ?>

<script>
	let barChart;
	const ctx = document.getElementById("myAreaChart");

	function loadChart(year) {
			$.ajax({
					url: 'model/monthly_sales.ajax.php',
					type: 'POST',
					dataType: 'json',
					data: { year: year },
					success: function (res) {

							if (barChart) {
									barChart.destroy();
							}

							barChart = new Chart(ctx, {
									type: 'bar',
									data: {
											labels: res.months,
											datasets: [{
													label: "Monthly Sales (" + year + ")",
													data: res.totals,
													backgroundColor: "rgba(78,115,223,0.8)"
											}]
									},
									options: {
											responsive: true,
											maintainAspectRatio: false,
											scales: {
													y: {
															beginAtZero: true,
															ticks: {
																	callback: value => "₦" + value.toLocaleString()
															}
													}
											}
									}
							});
					}
			});
	}

	/* Load default year */
	$(document).ready(function () {
			loadChart($('#yearSelector').val());

			$('#yearSelector').change(function () {
					loadChart($(this).val());
			});
	});
</script>

	<script>
		/* ========= AREA CHART ========= */
		// const areaCtx = document.getElementById("myAreaChart");

		// new Chart(areaCtx, {
		// 		type: 'line',
		// 		data: {
		// 				labels: <?= json_encode($months) ?>,
		// 				datasets: [{
		// 						label: "Sales (<?= $year ?>)",
		// 						data: <?= json_encode($monthlyTotals) ?>,
		// 						fill: true,
		// 						tension: 0.4,
		// 						backgroundColor: "rgba(78,115,223,0.15)",
		// 						borderColor: "rgba(78,115,223,1)",
		// 						pointRadius: 4
		// 				}]
		// 		},
		// 		options: {
		// 				maintainAspectRatio: false,
		// 				scales: {
		// 						y: {
		// 								beginAtZero: true,
		// 								ticks: {
		// 										callback: value => "₦" + value.toLocaleString()
		// 								}
		// 						}
		// 				}
		// 		}
		// });

		/* ========= PIE CHART ========= */
		const pieCtx = document.getElementById("myPieChart");

		new Chart(pieCtx, {
				type: 'pie',
				data: {
						labels: <?= json_encode($years) ?>,
						datasets: [{
								data: <?= json_encode($yearTotals) ?>,
								backgroundColor: [
										"#4e73df",
										"#1cc88a",
										"#36b9cc",
										"#f6c23e",
										"#e74a3b"
								]
						}]
				},
				options: {
						maintainAspectRatio: false,
						plugins: {
								legend: {
										position: 'bottom'
								}
						}
				}
		});
</script>
