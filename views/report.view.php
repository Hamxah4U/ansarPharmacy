<?php
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';
?>

<style>
    .container-fluid {
        max-height: 95vh;   
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>

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
									<input type="text" name="unit" class="form-control" placeholder="Type 'all' for all store">
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
	function formatMoney(value) {
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Global variable to hold group details for printing
let currentGroupReceipt = null;

$(document).ready(function () {
    $('#adminReport').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'model/user.report.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status && response.transactions.length > 0) {
                    $('#errorUnit, #errorProduct, #errorF, #errorS').text('');

                    // Group transactions by tCode
                    const grouped = {};
                    let grandTotal = 0;

                    response.transactions.forEach((row) => {
                        const code = row.tCode || 'N/A';
                        if (!grouped[code]) {
                            grouped[code] = {
                                customer: row.Customer,
                                seller: row.userfullname,
                                date: row.TransacDate,
                                time: row.TransacTime,
                                items: [],
                                groupTotal: 0
                            };
                        }
                        const amount = parseFloat(row.Amount) || 0;
                        grouped[code].items.push(row);
                        grouped[code].groupTotal += amount;
                        grandTotal += amount;
                    });

                    // Store global grouped object to access in modal
                    window.groupedTransactions = grouped;

                    let table = '<table class="table table-bordered">';
                    table += '<thead><tr><th>#</th><th>Product</th><th>Price</th><th>Qty</th><th>Amount</th><th>Date</th><th>Time</th><th>Action</th></tr></thead>';
                    table += '<tbody>';

                    let rowCounter = 1;

                    // Render Grouped Subheadings and Items
                    Object.keys(grouped).forEach((tCode) => {
                        const group = grouped[tCode];
                        
                        // Group Subheading Header
                        table += `
													<tr class="table-secondary">
															<td colspan="9">
																	<div class="d-flex justify-content-between align-items-center">
																			<div>
																					<strong>Customer:</strong> ${group.customer} &nbsp;|&nbsp; 
																					<strong>Transaction Code:</strong> ${tCode} &nbsp;|&nbsp; 
																					<strong>Total:</strong> &#8358; ${formatMoney(group.groupTotal)}
																			</div>
																			<div>
																					<button class="btn btn-sm btn-primary view-receipt-btn" data-code="${tCode}">
																							Receipt
																					</button>
																			</div>
																	</div>
															</td>
													</tr>`;

                        // Render Group Items
                        group.items.forEach((item) => {
                            table += `<tr>
                               <td>${rowCounter++}</td>
															<td>${item.dproduct}</td>
															<td>${formatMoney(item.Price)}</td>
															<td>${Number(item.qty).toLocaleString()}</td>
															<td>${formatMoney(item.Amount)}</td>
															<td>${item.TransacDate}</td>
															<td>${item.TransacTime}</td>
															<td>edit</td>
													</tr>`;
                        });
                    });

                    table += '</tbody></table>';
                    table += `<p class="mt-3 fs-5"><strong>Grand Total:</strong> &#8358; ${formatMoney(grandTotal)}</p>`;
                    
                    $('#reportResult').html(table);

                    const printButton = '<button id="printTable" class="btn btn-primary mb-3"><strong>Print Full Report</strong></button>';
                    $('#reportResult').prepend(printButton);

                    $('#printTable').on('click', function () {
                        const tableContent = $('#reportResult').html();
                        const printWindow = window.open('', '', 'height=600,width=800');
                        printWindow.document.write('<html><head><title>SFGE Report</title>');
                        printWindow.document.write('<style>');
                        printWindow.document.write(`
                            table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; }
                            table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            table th, .table-secondary { background-color: #f2f2f2; }
                            h3 { text-align: center; font-family: Arial, sans-serif; }
                            #printTable { display: none; }
                        `);
                        printWindow.document.write('</style></head><body>');
                        printWindow.document.write('<h3>Transaction Report</h3>');
                        printWindow.document.write(tableContent);
                        printWindow.document.write('</body></html>');
                        printWindow.document.close();
                        printWindow.print();
                    });

                } else {
                    $('#errorUnit').text(response.errors?.unit || '');
                    $('#errorProduct').text(response.errors?.product || '');
                    $('#errorF').text(response.errors?.startDate || '');
                    $('#errorS').text(response.errors?.endDate || '');
                    $('#reportResult').html('<p>No records found.</p>');
                }
            },
            error: function (xhr, status, error) {
                alert('Error: ' + status + ' - ' + error);
            }
        });
    });

    // Handle Receipt Modal Open
    $(document).on('click', '.view-receipt-btn', function () {
        const tCode = $(this).data('code');
        const group = window.groupedTransactions[tCode];
        currentGroupReceipt = group;

        let receiptHTML = `
            <div id="receiptContent" style="font-family: Arial, sans-serif; font-size: 14px;">
                <div class="text-center mb-3">
                    <h4 style="margin:0;"><?= $storeName; ?></h4>
                    <p style="margin:0;"><?= $state.' | '. $phone?></p>
                    <hr>
                </div>
                <p><strong>Customer:</strong> ${group.customer}</p>
                <p><strong>Transaction Code:</strong> ${tCode}</p>
                <p><strong>Seller:</strong> ${group.seller}</p>
                <p><strong>Date/Time:</strong> ${group.date} ${group.time}</p>
                <table class="table table-bordered table-sm mt-3">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>`;

        group.items.forEach(item => {
            receiptHTML += `
                <tr>
                    <td>${item.dproduct}</td>
                    <td>${Number(item.qty).toLocaleString()}</td>
                    <td>&#8358; ${formatMoney(item.Price)}</td>
                    <td>&#8358; ${formatMoney(item.Amount)}</td>
                </tr>`;
        });

        receiptHTML += `
                    </tbody>
                </table>
                <div class="text-end fw-bold mt-2">
                    <h5>Total: &#8358; ${formatMoney(group.groupTotal)}</h5>
                </div>
            </div>`;

        $('#receiptModalBody').html(receiptHTML);
        $('#receiptModal').modal('show');
    });

    // Handle Printable Receipt
    $('#printReceiptBtn').on('click', function () {
        const receiptContent = $('#receiptContent').html();
        const printWindow = window.open('', '', 'height=600,width=500');
        printWindow.document.write('<html><head><title>Print Receipt</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { font-family: Arial, sans-serif; margin: 20px; }
            .text-center { text-align: center; }
            .text-end { text-align: right; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            table th, table td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 12px; }
            hr { border: 0.5px solid #ccc; }
        `);
        printWindow.document.write('</style></head><body>');
        printWindow.document.write(receiptContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
});
</script>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="receiptModalLabel">Transaction Receipt</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-danger">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="receiptModalBody">
        <!-- Receipt Content Injected via JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="printReceiptBtn"><i class="fas fa-print"></i> Print Receipt</button>
      </div>
    </div>
  </div>
</div>