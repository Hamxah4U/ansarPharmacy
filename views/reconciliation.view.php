<?php
    require 'partials/security.php';
    require 'partials/header.php';
    require 'model/Database.php';

    $query = "SELECT SupplyID, ProductName, productcode, Quantity, StockQuantity, ExpiryDate FROM supply_tbl WHERE Status = 'Active' ORDER BY ProductName ASC";
    $stmt = $db->conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* Custom Table & Badge Styling */
    .badge-pending { background-color: #eaecf4; color: #5a5c69; border: 1px solid #d1d3e2; }
    .badge-match { background-color: #1cc88a; color: #fff; }
    .badge-mismatch { background-color: #e74a3b; color: #fff; }

    /* Styles for browser print */
    @media print {
        #accordionSidebar, .topbar, .card-header, .btn, footer, 
        .dataTables_length, .dataTables_filter, .dataTables_info, 
        .dataTables_paginate, .dt-buttons, .action-col {
            display: none !important;
        }
        #wrapper, #content-wrapper, #content, .container-fluid {
            margin: 0 !important; padding: 0 !important; width: 100% !important; background: #fff !important;
        }
        .card { border: none !important; box-shadow: none !important; }
        .physical-input { border: none !important; background: transparent !important; }
        .print-header { display: block !important; margin-bottom: 20px; text-align: center; }
    }

    .print-header { display: none; }
</style>

<!-- Page Wrapper -->
<div id="wrapper">
    <?php require 'partials/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php require 'partials/nav.php'; ?>

            <div class="container-fluid">

                <!-- Print Header (Visible during print mode) -->
                <div class="print-header mb-4">
                    <h2>Stock Reconciliation & Audit Report</h2>
                    <p class="mb-0">Generated on: <?= date('F d, Y h:i A') ?> | Auditor: <?= htmlspecialchars($_SESSION['email'] ?? 'Admin') ?></p>
                </div>

                <!-- Page Heading & Controls -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-balance-scale text-primary mr-2"></i>Stock Reconciliation & Audit
                    </h1>
                    
                    <div>
                        <!-- Suggestion 3: Dynamic Filter Button -->
                        <button id="toggleFilterBtn" class="btn btn-sm btn-outline-danger shadow-sm mr-2" data-filter="all">
                            <i class="fas fa-filter mr-1"></i> Show Mismatches Only
                        </button>

                        <!-- Suggestion 4: Batch Action Button -->
                        <button id="verifyAllBtn" class="btn btn-sm btn-success shadow-sm">
                            <i class="fas fa-check-double mr-1"></i> Verify All Entered
                        </button>
                    </div>
                </div>

                <!-- Info Alert Card -->
                <div class="card border-left-primary shadow mb-4">
                    <div class="card-body py-3">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Physical vs Virtual Stock Auditor
                                </div>
                                <div class="h6 mb-0 text-gray-800">
                                    Input counted physical stock and click <strong>Verify</strong> to evaluate differences, log records to the database, and print full audit sheets.
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- <i class="fas fa-boxes fa-2x text-gray-300"></i> -->
                                <a href="/log_history" class="btn btn-sm btn-info shadow-sm ml-3">
                                    <i class="fas fa-history mr-1"></i>Log history
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Audit Table Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Inventory Items</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="stockAuditTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Expiry Date</th>
                                        <th class="text-center">Virtual Stock</th>
                                        <th class="text-center" style="width: 140px;">Physical Count</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center action-col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $index =>  $row): 
                                        $virtualStock = (isset($row['StockQuantity']) && $row['StockQuantity'] > 0) ? $row['StockQuantity'] : $row['Quantity'];
                                    ?>
                                        <tr id="row-<?= $row['SupplyID'] ?>" data-status="pending">
                                            <td class="align-middle">
                                                <span><?= htmlspecialchars($index + 1) ?></span>
                                            </td>
                                            <td class="align-middle font-weight-bold text-gray-800 product-name">
                                                <?= htmlspecialchars($row['ProductName']) ?>
                                            </td>
                                            <td class="align-middle">
                                                <small class="text-muted"><?= htmlspecialchars($row['ExpiryDate']) ?></small>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="h5 font-weight-bold text-primary mb-0 virtual-val">
                                                    <?= (int)$virtualStock ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="number" 
                                                       class="form-control text-center physical-input font-weight-bold" 
                                                       min="0" 
                                                       placeholder="Count"
                                                       data-id="<?= $row['SupplyID'] ?>" 
                                                       data-virtual="<?= (int)$virtualStock ?>">
                                            </td>
                                            <td class="align-middle text-center status-cell">
                                                <span class="badge badge-pending px-3 py-2">Pending Count</span>
                                            </td>
                                            <td class="align-middle text-center action-col">
                                                <button type="button" 
                                                        class="btn btn-sm btn-primary verify-btn shadow-sm"
                                                        data-id="<?= $row['SupplyID'] ?>">
                                                    <i class="fas fa-check-circle mr-1"></i> Verify
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
<?php require 'partials/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Suggestion 2: DataTables with Full Export/Print capability
        var table = $('#stockAuditTable').DataTable({
            "pageLength": 100,
            "ordering": true,
            dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-right"B>>' +
                '<"row"<"col-md-12"tr>>' +
                '<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print mr-1"></i> Print All Pages',
                    className: 'btn btn-secondary btn-sm shadow-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
                        format: {
                            body: function(data, row, column, node) {
                                // Fetch actual input values for exported physical counts
                                if (column === 4) {
                                    return $(node).find('input').val() || '0';
                                }
                                // Strip HTML tags for clean export text
                                return $(node).text().trim();
                            }
                        }
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel mr-1"></i> Excel Export',
                    className: 'btn btn-success btn-sm shadow-sm ml-1',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf mr-1"></i> PDF Export',
                    className: 'btn btn-danger btn-sm shadow-sm ml-1',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                }
            ]
        });

        // Keep DOM input values updated for accurate printing
        $('#stockAuditTable').on('input', '.physical-input', function() {
            $(this).attr('value', $(this).val());
        });

        // Verification and Database Logging Function
        function checkStock(row) {
            let input = row.find('.physical-input');
            let physicalVal = input.val().trim();
            let virtualVal = parseFloat(input.data('virtual'));
            let supplyId = input.data('id');
            let statusCell = row.find('.status-cell');
            let productName = row.find('.product-name').text().trim();

            if (physicalVal === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Input Missing',
                    text: 'Please enter a physical count.',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }

            let physicalCount = parseFloat(physicalVal);
            let discrepancy = physicalCount - virtualVal;
            let statusText = (physicalCount === virtualVal) ? 'Match' : 'Mismatch';

            // Update status UI & row attribute
            if (statusText === 'Match') {
                statusCell.html('<span class="badge badge-match px-3 py-2"><i class="fas fa-check mr-1"></i> Match</span>');
                row.attr('data-status', 'match');
            } else {
                let diffLabel = discrepancy > 0 ? `+${discrepancy} Over` : `${discrepancy} Deficit`;
                statusCell.html(`<span class="badge badge-mismatch px-3 py-2"><i class="fas fa-exclamation-triangle mr-1"></i> Mismatch (${diffLabel})</span>`);
                row.attr('data-status', 'mismatch');
            }

            // Suggestion 1: AJAX request to record audit in database
            $.ajax({
                url: 'model/reconciliation_save_log.php',
                type: 'POST',
                data: {
                    supply_id: supplyId,
                    product_name: productName,
                    virtual_stock: virtualVal,
                    physical_stock: physicalCount,
                    discrepancy: discrepancy,
                    status: statusText
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (statusText === 'Match') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Stock Verified & Logged!',
                                html: `<strong>${productName}</strong> stock matches perfectly.<br><span class="badge badge-success p-2 mt-2">Stock: ${physicalCount} units</span>`,
                                confirmButtonColor: '#1cc88a'
                            });
                        } else {
                            let diffText = discrepancy > 0 ? `+${discrepancy} Over` : `${discrepancy} Deficit`;
                            Swal.fire({
                                icon: 'error',
                                title: 'Inconsistency Saved!',
                                html: `Logged discrepancy for <strong>${productName}</strong>.<br><br>` +
                                    `<strong>Physical:</strong> ${physicalCount} | <strong>Virtual:</strong> ${virtualVal}<br>` +
                                    `<strong>Difference:</strong> <span class="text-danger font-weight-bold">${diffText}</span>`,
                                confirmButtonColor: '#e74a3b'
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Audit Warning',
                            text: 'Stock checked locally, but log failed: ' + response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Unable to connect to server to log this audit.'
                    });
                }
            });
        }

        // Suggestion 4: Batch Verification Function ("Verify All")
        $('#verifyAllBtn').on('click', function() {
            let rowsToProcess = [];

            // Loop through all physical input fields across all DataTables pages
            table.$('.physical-input').each(function() {
                let input = $(this);
                let val = input.val().trim();

                if (val !== "") {
                    let row = input.closest('tr');
                    let physicalCount = parseFloat(val);

                    // Validation Guard: Ensure input is a valid non-negative number
                    if (!isNaN(physicalCount) && physicalCount >= 0) {
                        let virtualVal = parseFloat(input.data('virtual'));
                        let supplyId = input.data('id');
                        let productName = row.find('.product-name').text().trim();
                        let discrepancy = physicalCount - virtualVal;
                        let statusText = (physicalCount === virtualVal) ? 'Match' : 'Mismatch';

                        rowsToProcess.push({
                            row: row,
                            input: input,
                            supplyId: supplyId,
                            productName: productName,
                            virtualVal: virtualVal,
                            physicalCount: physicalCount,
                            discrepancy: discrepancy,
                            statusText: statusText
                        });
                    }
                }
            });

            if (rowsToProcess.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Data Entered',
                    text: 'Please enter physical stock counts for at least one item before clicking "Verify All".',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }

            // Confirm batch process
            Swal.fire({
                title: 'Verify ' + rowsToProcess.length + ' Items?',
                text: 'This will update status badges and log all entered items into the audit log database.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Yes, Verify & Save All'
            }).then((result) => {
                if (result.isConfirmed) {
                    let successCount = 0;
                    let promises = [];

                    // Show loading alert
                    Swal.fire({
                        title: 'Processing Audits...',
                        html: 'Saving reconciliation records to database.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Process each row via AJAX
                    rowsToProcess.forEach(function(item) {
                        let statusCell = item.row.find('.status-cell');

                        // Update UI visually
                        if (item.statusText === 'Match') {
                            statusCell.html('<span class="badge badge-match px-3 py-2"><i class="fas fa-check mr-1"></i> Match</span>');
                            item.row.attr('data-status', 'match');
                        } else {
                            let diffLabel = item.discrepancy > 0 ? `+${item.discrepancy} Over` : `${item.discrepancy} Deficit`;
                            statusCell.html(`<span class="badge badge-mismatch px-3 py-2"><i class="fas fa-exclamation-triangle mr-1"></i> Mismatch (${diffLabel})</span>`);
                            item.row.attr('data-status', 'mismatch');
                        }

                        // Push AJAX request to promise array
                        let request = $.ajax({
                            url: 'model/reconciliation_save_log.php',
                            type: 'POST',
                            data: {
                                supply_id: item.supplyId,
                                product_name: item.productName,
                                virtual_stock: item.virtualVal,
                                physical_stock: item.physicalCount,
                                discrepancy: item.discrepancy,
                                status: item.statusText
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) successCount++;
                            }
                        });

                        promises.push(request);
                    });

                    // When all AJAX requests complete
                    $.when.apply($, promises).always(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Batch Audit Complete!',
                            text: `Successfully verified and logged ${successCount} of ${rowsToProcess.length} items to the database.`,
                            confirmButtonColor: '#1cc88a'
                        });

                        // Redraw table in case "Show Mismatches Only" filter is active
                        table.draw();
                    });
                }
            });
        });

        // Verify Button Event Listener
        $('#stockAuditTable').on('click', '.verify-btn', function() {
            let row = $(this).closest('tr');
            checkStock(row);
        });

        // Enter Key Listener inside Input
        $('#stockAuditTable').on('keypress', '.physical-input', function(e) {
            if (e.which === 13) {
                let row = $(this).closest('tr');
                checkStock(row);
            }
        });

        // Suggestion 3: Custom DataTables Filter for Showing Mismatches Only
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            let filterMode = $('#toggleFilterBtn').attr('data-filter');
            if (filterMode === 'all') {
                return true;
            }
            
            // Retrieve the tr element corresponding to dataIndex
            let node = settings.aoData[dataIndex].nTr;
            let rowStatus = $(node).attr('data-status');
            
            return rowStatus === 'mismatch';
        });

        // Toggle Filter Button Listener
        $('#toggleFilterBtn').on('click', function() {
            let currentFilter = $(this).attr('data-filter');
            
            if (currentFilter === 'all') {
                $(this).attr('data-filter', 'mismatches');
                $(this).removeClass('btn-outline-danger').addClass('btn-danger');
                $(this).html('<i class="fas fa-list mr-1"></i> Show All Items');
            } else {
                $(this).attr('data-filter', 'all');
                $(this).removeClass('btn-danger').addClass('btn-outline-danger');
                $(this).html('<i class="fas fa-filter mr-1"></i> Show Mismatches Only');
            }
            
            // Redraw DataTables view
            table.draw();
        });
    });
</script>