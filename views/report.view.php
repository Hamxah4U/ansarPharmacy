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
		@media print {
    body * {
        visibility: hidden;
    }
    #receiptModalBody, #receiptModalBody * {
        visibility: visible;
    }
    #receiptModalBody {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
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

let currentGroupReceipt = null;

$(document).ready(function () {
    // Form submission handler to fetch report
    $('#adminReport').on('submit', function (e) {
        if (e) e.preventDefault();
        $.ajax({
            url: 'model/user.report.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status && response.transactions.length > 0) {
                    $('#errorUnit, #errorProduct, #errorF, #errorS').text('');

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

                    window.groupedTransactions = grouped;

                    let table = '<table class="table table-bordered">';
                    table += '<thead><tr><th>#</th><th>Product</th><th>Price</th><th>Qty</th><th>Amount</th><th>Date</th><th>Time</th><th>Action</th></tr></thead>';
                    table += '<tbody>';

                    let rowCounter = 1;

                    Object.keys(grouped).forEach((tCode) => {
                        const group = grouped[tCode];
                        
                        table += `
                            <tr class="table-secondary">
                                <td colspan="8">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Customer:</strong> ${group.customer} &nbsp;|&nbsp; 
                                            <strong>Transaction Code:</strong> ${tCode} &nbsp;|&nbsp; 
                                            <strong>Total:</strong> &#8358; ${formatMoney(group.groupTotal)}
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-primary view-receipt-btn" data-code="${tCode}">Receipt</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;

                        group.items.forEach((item) => {
                            table += `<tr>
                                <td>${rowCounter++}</td>
                                <td>${item.dproduct}</td>
                                <td>${formatMoney(item.Price)}</td>
                                <td>${Number(item.qty).toLocaleString()}</td>
                                <td>${formatMoney(item.Amount)}</td>
                                <td>${item.TransacDate}</td>
                                <td>${item.TransacTime}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-trans-btn" 
                                            data-tid="${item.TID}" 
                                            data-product="${item.dproduct}" 
                                            data-price="${item.Price}" 
                                            data-qty="${item.qty}">
                                        Edit <span class="fas fa-edit"></span>
                                    </button>

																		<button class="btn btn-sm btn-danger return-trans-btn" 
																			data-tid="${item.TID}" data-product="${item.dproduct}" 
																			data-price="${item.Price}" data-qty="${item.qty}">Return</button>
                                </td>
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
                        printWindow.document.write('<html><head><title>SFGE Report</title><style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; } table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; } table th, .table-secondary { background-color: #f2f2f2; } h3 { text-align: center; font-family: Arial, sans-serif; } #printTable { display: none; }</style></head><body><h3>Transaction Report</h3>' + tableContent + '</body></html>');
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

    // Handle Edit Button Click
    $(document).on('click', '.edit-trans-btn', function () {
        const tid = $(this).data('tid');
        const product = $(this).data('product');
        const price = $(this).data('price');
        const qty = $(this).data('qty');

        $('#edit_tid').val(tid);
        $('#edit_product_name').val(product);
        $('#edit_price').val(price);
        $('#edit_qty').val(qty);
        $('#edit_amount').val(formatMoney(price * qty));

        $('#editModal').modal('show');
    });

    // Auto recalculate amount on input
    $('#edit_price, #edit_qty').on('input', function () {
        const p = parseFloat($('#edit_price').val()) || 0;
        const q = parseInt($('#edit_qty').val()) || 0;
        $('#edit_amount').val(formatMoney(p * q));
    });

    // Save Edited Transaction via AJAX
    $('#editTransactionForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'model/update.transaction.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (res) {
                if (res.status) {
                    alert(res.message);
                    $('#editModal').modal('hide');
                    $('#adminReport').trigger('submit'); // Reload updated table data
                } else {
                    alert(res.message);
                }
            },
            error: function (xhr, status, error) {
                alert('Update request failed: ' + error);
            }
        });
    });

    // View Receipt Modal Handler
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
                    <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
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

    // Printable Receipt Handler
    $('#printReceiptBtn').on('click', function () {
        const receiptContent = $('#receiptContent').html();
        const printWindow = window.open('', '', 'height=600,width=500');
        printWindow.document.write('<html><head><title>Print Receipt</title><style>body { font-family: Arial, sans-serif; margin: 20px; } .text-center { text-align: center; } .text-end { text-align: right; } table { width: 100%; border-collapse: collapse; margin-top: 10px; } table th, table td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 12px; } hr { border: 0.5px solid #ccc; }</style></head><body>' + receiptContent + '</body></html>');
        printWindow.document.close();
        printWindow.print();
    });

		$(document).on('click', '.return-trans-btn', function () {
    const tid = $(this).data('tid');
    const product = $(this).data('product');
    const price = parseFloat($(this).data('price'));
    const qty = parseInt($(this).data('qty'));

    $('#return_tid').val(tid);
    $('#return_product_name').val(product);
    $('#return_purchased_qty').val(qty);
    $('#return_qty').attr('max', qty).val(1);
    $('#return_refund_amount').val(formatMoney(price * 1));

    // Recalculate refund amount dynamically on quantity change
    $('#return_qty').off('input').on('input', function () {
        const reqQty = parseInt($(this).val()) || 0;
        $('#return_refund_amount').val(formatMoney(price * reqQty));
    });

    $('#returnModal').modal('show');
});

	// Submit Return Form
	$('#returnProductForm').on('submit', function (e) {
			e.preventDefault();
			if (!confirm('Are you sure you want to process this return? Money will be refunded and inventory restored.')) {
					return;
			}

			$.ajax({
					url: 'model/return.product.php',
					type: 'POST',
					dataType: 'json',
					data: $(this).serialize(),
					success: function (res) {
							alert(res.message);
							if (res.status) {
									$('#returnModal').modal('hide');
									$('#adminReport').trigger('submit'); // Reload updated table
							}
					},
					error: function (xhr, status, error) {
							alert('Return request failed: ' + error);
					}
			});
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


<!-- Edit Transaction Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editTransactionForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Item Transaction</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-danger">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="tid" id="edit_tid">
            <div class="form-group mb-3">
                <label>Product Name</label>
                <input type="text" id="edit_product_name" class="form-control" readonly>
            </div>
            <div class="form-group mb-3">
                <label>Price (&#8358;)</label>
                <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required min="0">
            </div>
            <div class="form-group mb-3">
                <label>Quantity</label>
                <input type="number" name="qty" id="edit_qty" class="form-control" required min="1">
            </div>
            <div class="form-group mb-3">
                <label>Calculated Amount (&#8358;)</label>
                <input type="text" id="edit_amount" class="form-control" readonly>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Return Product Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="returnProductForm">
        <div class="modal-header">
          <h5 class="modal-title" id="returnModalLabel">Process Product Return</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-danger">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="tid" id="return_tid">
            <div class="form-group mb-3">
                <label>Product Name</label>
                <input type="text" id="return_product_name" class="form-control" readonly>
            </div>
            <div class="form-group mb-3">
                <label>Purchased Quantity</label>
                <input type="text" id="return_purchased_qty" class="form-control" readonly>
            </div>
            <div class="form-group mb-3">
                <label>Return Quantity</label>
                <input type="number" name="return_qty" id="return_qty" class="form-control" required min="1">
            </div>
            <div class="form-group mb-3">
                <label>Refund Amount (&#8358;)</label>
                <input type="text" id="return_refund_amount" class="form-control" readonly>
            </div>
            <div class="form-group mb-3">
                <label>Reason for Return</label>
                <textarea name="reason" class="form-control" rows="2" placeholder="e.g. Expired, Damaged, Wrong item"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Confirm Refund & Restore Stock</button>
        </div>
      </form>
    </div>
  </div>
</div>