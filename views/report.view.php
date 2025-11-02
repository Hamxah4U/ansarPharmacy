<?php
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';
?>

    <!-- Page Wrapper -->
<div id="wrapper">
	<!-- Sidebar -->
	<?php require 'partials/sidebar.php' ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- Topbar -->
        <?php  require 'partials/nav.php';?>

				<!-- Begin Page Content -->
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800"></h1>
						<a href="#" clas="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i clas="fas fa-download fa-sm text-white-50"></i></a>
					</div>
						<!-- Content Row -->
					<div class="container mt-4">
						<form id="adminReport">
						<input type="text" value="all" name="unit" class="form-control" placeholder="Type 'all' for all dpt" hidden>
						<input type="text" value="%" name="product" class="form-control" placeholder="Type % for all products" hidden>

							<!-- <div class="row mb-3">
								<div class="col-md-2">
									<label for="unit">Unit:</label>
								</div>
								<div class="col-md-4">
									<input type="text" name="unit" class="form-control" placeholder="Type 'all' for all dpt">
									<small class="text-danger" id="errorUnit"></small>
								</div>
								<div class="col-md-2">
									<label for="product">Product:</label>
								</div>
								<div class="col-md-4">
									<input type="text" name="product" class="form-control" placeholder="Type % for all products">
									<small class="text-danger" id="errorProduct"></small>
								</div>
							</div> -->

							<div class="row mb-3">
								<div class="col-md-2">
									<label for="sdate">Start Date:</label>
								</div>
								<div class="col-md-4">
									<input type="date" name="sdate" class="form-control" id="sdate">
									<small class="text-danger" id="errorF"></small>
								</div>
								<div class="col-md-2">
									<label for="edate">End Date:</label>
								</div>
								<div class="col-md-4">
									<input type="date" name="edate" class="form-control" id="edate">
									<small class="text-danger" id="errorS"></small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-md-2">
									<label for="status">Status:</label>
								</div>
								<div class="col-md-4">
									<select name="status" id="status" class="form-control">
										<option value="Paid">Paid</option>
										<option value="Not-Paid">Not-Paid</option>
									</select>
								</div>
								<div class="col-md-2">
									<label for="user">User:</label>
								</div>
								<div class="col-md-4">
								<select name="user" id="user" class="form-control">
									<option value="all">All</option>
									<?php
											$stmt = $db->query("SELECT * FROM `users_tbl` WHERE `Email`!= 'hamxah4u@gmail.com' ORDER BY `Fullname` ASC");
											foreach($stmt as $users): ?>
											<option value="<?= $users['Email'] ?>"><?= $users['Fullname'] . ' ~ ' . $users['Email'] ?></option>
									<?php endforeach ?>
								</select>

								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<!-- <button type="button" id="btn2" class="btn btn-danger" onclick="PrintDoc()">
										<i class="icofont-print"></i> Print
									</button> -->
								</div>
								<div class="col-md-6 text-end">
									<button type="submit" class="btn btn-primary"><strong>Search</strong></button>
									<!-- <input type="submit" class="btn btn-primary" value="Search__"> -->
								</div>
							</div>
						</form>
					</div>
					<div id="reportResult" class="table-responsive"></div>

					<div id="reportResults" class="mt-4 table-responsive"></div>
				</div>
      </div>

<?php require 'partials/footer.php'; ?>
<script>
$(document).ready(function () {
    $('#adminReport').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'model/user.report.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status) {
                    $('#errorUnit, #errorProduct, #errorF, #errorS').text('');
                    let totalAmount = 0;
                    let table = '<table class="table table-bordered table-striped">';
                    table += '<thead><tr><th>#</th><th>Code</th><th>Product</th><th>Price</th><th>Qty</th><th>Amount</th><th>Customer</th><th>Date</th><th>Time</th></tr></thead>';
                    table += '<tbody>';
                    response.transactions.forEach((row, index) => {
                        totalAmount += parseFloat(row.Amount);  // Sum the amount
                        table += `<tr>
                            <td>${index + 1}</td>
                            <td>${row.tCode}</td>
                            
                            <td>${row.dproduct}</td>
                            <td>${row.Price}</td>
                            <td>${row.qty}</td>
                            <td>${row.Amount}</td>
                            <td>${row.Customer}</td>
                            <td>${row.TransacDate}</td>
                            <td>${row.TransacTime}</td>
                            
                        </tr>`;
                    });
                    table += '</tbody></table>';
										table += `<p><strong>Total Amount:</strong> &#8358; ${totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>`;
                    // table += `<p><strong>Total___ Amount:</strong> ${totalAmount.toFixed(2)}</p>`;
                    $('#reportResult').html(table);
                    
                    const printButton = '<button id="printTable" class="btn btn-primary"><strong>Print Report</strong></button>';
                    $('#reportResult').append(printButton);
                    $('#printTable').on('click', function () {
                        const tableContent = $('#reportResult').html();
                        const printWindow = window.open('', '', 'height=600,width=800');
                        printWindow.document.write('<html><head><title>SFGE Report</title>');
                        printWindow.document.write('<style>');
                        printWindow.document.write(`
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                font-family: Arial, sans-serif;
                                font-size: 12px;
                            }
                            table th, table td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: left;
                            }
                            table th {
                                background-color: #f2f2f2;
                            }
                            table tr:nth-child(even) {
                                background-color: #f9f9f9;
                            }
                            table tr:nth-child(odd) {
                                background-color: #fff;
                            }
                            table tr:hover {
                                background-color: #ddd;
                            }
                            h3 {
                                text-align: center;
                                font-family: Arial, sans-serif;
                            }
                        `);
                        printWindow.document.write('</style>');
                        printWindow.document.write('</head><body>');
                        printWindow.document.write('<h3>Transaction Report</h3>');
                        printWindow.document.write(tableContent);
                        printWindow.document.write('</body></html>');
                        printWindow.document.close();
                        printWindow.print();
                    });
                } else {
                    $('#errorUnit').text(response.errors.unit || '');
                    $('#errorProduct').text(response.errors.product || '');
                    $('#errorF').text(response.errors.startDate || '');
                    $('#errorS').text(response.errors.endDate || '');
                    $('#reportResult').html('<p>No records found.</p>');
                }
            },
            error: function (xhr, status, error) {
                alert('Error: ' + status + ' - ' + error);
            }
        });
    });
});

	
	/* $(document).ready(function () {
			$('#adminReport').on('submit', function (e) {
					e.preventDefault();
					$.ajax({
							url: 'model/user.report.php',
							type: 'POST',
							dataType: 'json',
							data: $(this).serialize(),
							success: function (response) {
									if (response.status) {
											
											$('#errorUnit, #errorProduct, #errorF, #errorS').text('');
											let totalAmount = 0
											let table = '<table class="table table-bordered table-striped">';
											table += '<thead><tr><th>#</th><th>Code</th><th>Store</th><th>Product</th><th>Price</th><th>Qty</th><th>Amount</th><th>Customer</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>';
											table += '<tbody>';
											response.transactions.forEach((row, index) => {
												totalAmount += parseFloat(row.Amount);
													table += `<tr>
															<td>${index + 1}</td>
															<td>${row.tCode}</td>
															<td>${row.Unit}</td>
															<td>${row.dproduct}</td>
															<td>${row.Price}</td>
															<td>${row.qty}</td>
															<td>${row.Amount}</td>
															<td>${row.Customer}</td>
															<td>${row.TransacDate}</td>
															<td>${row.TransacTime}</td>
															<td>${row.Status}</td>
													</tr>`;
													table += `<tr>
														<td colspan='6'><strong>Total</strong></td>
														<td><strong>${totalAmount.toFixed(2)}</strong></td>
													</tr>`;

											});
											table += '</tbody></table>';
											$('#reportResult').html(table);

											
											const printButton = '<button id="printTable" class="btn btn-primary">Print Report</button>';
											$('#reportResult').append(printButton);

											$('#printTable').on('click', function () {
												const tableContent = $('#reportResult').html();
												const printWindow = window.open('', '', 'height=600,width=800');
												printWindow.document.write('<html><head><title>SFGE Report</title>');
												printWindow.document.write('<style>');
												printWindow.document.write(`
													table {
														width: 100%;
														border-collapse: collapse;
														font-family: Arial, sans-serif;
														font-size: 12px;
													}
													table th, table td {
														border: 1px solid #ddd;
														padding: 8px;
														text-align: left;
													}
													table th {
														background-color: #f2f2f2;
													}
													table tr:nth-child(even) {
														background-color: #f9f9f9;
													}
													table tr:nth-child(odd) {
														background-color: #fff;
													}
													table tr:hover {
														background-color: #ddd;
													}
													h3 {
														text-align: center;
														font-family: Arial, sans-serif;
													}
												`);
												printWindow.document.write('</style>');
												printWindow.document.write('</head><body>');
												printWindow.document.write('<h3>Transaction Report</h3>');
												printWindow.document.write(tableContent);
												printWindow.document.write('</body></html>');
												printWindow.document.close();
												printWindow.print();
											});
									} else {
											$('#errorUnit').text(response.errors.unit || '');
											$('#errorProduct').text(response.errors.product || '');
											$('#errorF').text(response.errors.startDate || '');
											$('#errorS').text(response.errors.endDate || '');
											$('#reportResult').html('<p>No records found.</p>');
									}
							},
							error: function (xhr, status, error) {
									alert('Error: ' + status + ' - ' + error);
							}
					});
			});
	}); */
</script>