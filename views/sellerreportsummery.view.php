<?php
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';

?>

    <!-- Page Wrapper -->
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>

  <!-- End of Sidebar -->

    <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
		<!-- Main Content -->
		<div id="content" >
			<?php
					require 'partials/nav.php';
			?>
			<div class="container-fluid">
					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
							<h1 class="h3 mb-0 text-gray-800"></h1>
							<button onclick="PrintDoc2()" type="button" class="btn btn-primary"><strong>Print Report <span class="icofont-print"></span> </strong></button>
					</div>
					<!-- Content Row -->
					<div id="contentToPrint">
						<!-- <img src="../img/SHAFA.png" alt="" width="100%"> -->
						
						<table class="table table-striped">							
							<thead>
								<tr><th colspan="5" style="text-align: center;">Today's Sales Summary</th></tr>
								<tr>
									<th>#</th>
									<th>Store</th>
									<th>Product</th>
									<th>Quantity</th>
									<th>Amount(&#8358;)</th>
								</tr>								
							</thead>
							<tbody>
								<?php
									$stmt = $db->conn->prepare("SELECT COALESCE(SUM(qty), 0) AS totalQty, COALESCE(SUM(Amount), 0) AS totalAmount, ProductName, department_tbl.Department FROM `transaction_tbl`
									JOIN supply_tbl ON supply_tbl.SupplyID = `Product` JOIN `department_tbl` ON `department_tbl`.`deptID` = tDepartment
									WHERE DATE(`TransacDate`) = DATE(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."' GROUP BY tDepartment, Product ORDER BY totalQty DESC");
									$stmt->execute();
									$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
									$total = 0;
									$totalqty = 0;
									foreach($reports as $index => $report):
										$total += $report['totalAmount'];
										$totalqty += $report['totalQty'];
									?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td><?= $report['Department'] ?></td>
										<td><?= $report['ProductName'] ?></td>
										<td><?= $report['totalQty'] ?></td>	
										<td><?= number_format($report['totalAmount']) ?></td>									
									</tr>
								<?php endforeach ?>
							</tbody>
							<tr>
								<td colspan="3"><strong>Total</strong></td>
								<td><strong><?= $totalqty ?></strong></td>
								<td><strong>&#8358;<?= number_format($total, 2) ?></strong></td>
							</tr>
						</table>
						<br/> <br />

						<table class="table table-striped">							
							<thead>
								<tr><th colspan="6" style="text-align: center;">Today's Payment from Creditors</th></tr>
								<tr>
									<th>#</th>
									<th>Customers</th>
									<th>Phone</th>
									<th>Amount(&#8358;)</th>
								</tr>								
							</thead>
							<tbody>
								<?php
									$stmtpcredit = $db->query("SELECT SUM(COALESCE(Amount, 0)) as p_from_creditors, tCode, CID, narration, Customer
									FROM transaction_tbl 
									WHERE `Status` = 'Paid' AND creditstatus = 'settlement' AND DATE(`TransacDate`) = DATE(CURRENT_DATE) AND `TrasacBy` = '".$_SESSION['email']."' GROUP BY CID, tCode");
									
									$pcreditors = $stmtpcredit->fetchAll(PDO::FETCH_ASSOC);
									$totalpcredit = 0;
									$totalqty = 0;
									foreach($pcreditors as $index => $pcreditor):
										$totalpcredit += $pcreditor['p_from_creditors'];
									?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td><?= $pcreditor['Customer'] ?></td>	
										<td><?= $pcreditor['tCode'] ?></td>	
										<td><?= number_format($pcreditor['p_from_creditors']) ?></td>		

									</tr>
								<?php endforeach ?>
							</tbody>
							<tr>
								<td colspan="3"><strong>Total</strong></td>
								<td><strong>&#8358;<?= number_format($totalpcredit, 2) ?></strong></td>
							</tr>
						</table>
						<br/> <br />

						<table class="table table-striped">
							<tr><th colspan="5" style="text-align: center;">Today's Finance Summary</th></tr>
							<tr>
								<th>#</th>
								<th>Cash(&#8358;)</th>
								<th>Transfer(&#8358;)</th>
								<th>POS(&#8358;)</th>
								<th>Total(&#8358;)</th>
							</tr>
									<?php										
										$stmttotal = $db->query("SELECT COALESCE(SUM(Amount), 0) AS `dailyTotal` 
										FROM `transaction_tbl` WHERE `Status` = 'Paid' AND DATE(`TransacDate`) = CURRENT_DATE AND `TrasacBy` = '".$_SESSION['email']."' ");
										$dailytotal = $stmttotal->fetch(PDO::FETCH_ASSOC);

										$dailysql = $db->query("SELECT 
											SUM(Amount) AS ttamount, 
											COALESCE(SUM(CAST(cash AS DECIMAL(10,2))), 0) AS dcash, 
											COALESCE(SUM(CAST(pos AS DECIMAL(10,2))), 0) AS dpos, 
											COALESCE(SUM(CAST(transfer AS DECIMAL(10,2))), 0) AS dtransfer 
											FROM transaction_tbl 
											WHERE `Status` = 'Paid' 
											AND DATE(TransacDate) = CURRENT_DATE() 
											AND TID IN (
													SELECT MIN(TID) 
													FROM transaction_tbl 
													WHERE DATE(TransacDate) = CURRENT_DATE() AND `TrasacBy` = '".$_SESSION['email']."' 
													GROUP BY tCode
											)"
										);
										$dailyRow = $dailysql->fetch(PDO::FETCH_ASSOC);
									?>

							<tr>
								<td>1</td>
								<td><?= number_format($dailyRow['dcash'], 2, '.') ?></td>
								<td><?= number_format($dailyRow['dtransfer'], 2, '.') ?></td>
								<td><?= number_format($dailyRow['dpos'], 2, '.') ?></td>
								<td><?= number_format($dailytotal['dailyTotal'], 2, '.') ?></td>
							</tr>
						</table>
						<br /> <br />
						
						<table class="table table-striped">
							<thead>
								<tr><th colspan="5" style="text-align: center;">Today's Store Report Summary</th></tr>
								<tr>
									<th>#</th>
									<th>Store</th>
									<th>Product</th>
									<th>Quantity</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$stmt = $db->conn->prepare("SELECT COALESCE(SUM(`Quantity`), 0) as remainQty, department_tbl.Department, ProductName FROM supply_tbl JOIN department_tbl ON department_tbl.deptID = supply_tbl.Department GROUP BY `department_tbl`.`Department`, supply_tbl.ProductName");
									$stmt->execute();
									$stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach($stores as $index => $store):?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td><?= $store['Department'] ?></td>
										<td><?= $store['ProductName'] ?></td>
										<td><?= $store['remainQty'] ?></td>
										<td>Active</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
						<br/><br/>
						<strong><p style="margin: 0;">Printed By: <?= $_SESSION['fname']?>&nbsp; |&nbsp; Date: <?= date('D-M-Y h:i:s') ?></p></strong>
					</div>
					<!-- Content Row -->
			</div>
		</div>
<?php
  require 'partials/footer.php';
?>
<?php $date = date('d-m-Y') ?>
<script>
  function PrintDoc2() {
    const content = document.getElementById('contentToPrint').innerHTML;
    const newWindow = window.open('', '_blank', 'left=300,top=100,width=1000,height=700,toolbar=0,scrollbars=0,status=0');
    newWindow.document.write(`
      <html>
      <head>
        <title>Shafa_Global_Fertilize_refort_for_<?= $date ?>></title>
        <style>
          /* Include any styles required for the content */
          body {
            font-family: Arial, sans-serif;
          }
          table {
            width: 100%;
            border-collapse: collapse;
          }
          th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
          }
          .footer {
            text-align: center;
            margin-top: 20px;
          }
        </style>
      </head>
        <body>
          ${content}
        </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.onload = function () {
      newWindow.print();
    };
  }
</script>