<?php
  require 'partials/security.php';
  require 'partials/header.php';
  require 'model/Database.php';

  // 1. Fetch Metrics Summary
  $metricsQuery = "SELECT 
        COUNT(*) as TotalAudits,
        SUM(CASE WHEN Status = 'Match' THEN 1 ELSE 0 END) as TotalMatches,
        SUM(CASE WHEN Status = 'Mismatch' AND Discrepancy < 0 THEN 1 ELSE 0 END) as TotalDeficits,
        SUM(CASE WHEN Status = 'Mismatch' AND Discrepancy > 0 THEN 1 ELSE 0 END) as TotalSurpluses
      FROM stock_reconciliation_logs";
  $stmtMetrics = $db->conn->prepare($metricsQuery);
  $stmtMetrics->execute();
  $metrics = $stmtMetrics->fetch(PDO::FETCH_ASSOC);

  // 2. Fetch Detailed Audit Logs + Expiry Date from supply_tbl
  $logsQuery = "SELECT u.Fullname, l.id AS LogID, l.SupplyID, l.ProductName, l.VirtualStock, l.PhysicalStock, l.Discrepancy, l.Status, l.AuditedBy, l.AuditDate, s.ExpiryDate 
    FROM stock_reconciliation_logs l
    LEFT JOIN supply_tbl s ON l.SupplyID = s.SupplyID
    LEFT JOIN users_tbl u ON u.userID = l.AuditedBy 
    ORDER BY l.AuditDate DESC";
  $stmtLogs = $db->conn->prepare($logsQuery);
  $stmtLogs->execute();
  $logs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'partials/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php require 'partials/nav.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-history text-primary mr-2"></i>Stock Audit History Logs
                    </h1>
                    <a href="/inventory-reconciliation" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-balance-scale mr-1"></i> New Stock Reconciliation
                    </a>
                </div>

                <!-- Metrics Cards Row -->
                <div class="row">
                    <!-- Total Audits Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Audits Executed</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($metrics['TotalAudits'] ?? 0) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Matches Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Perfect Matches</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($metrics['TotalMatches'] ?? 0) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deficits Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stock Deficits (Missing)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($metrics['TotalDeficits'] ?? 0) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Surpluses Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock Surpluses (Extra)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($metrics['TotalSurpluses'] ?? 0) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-boxes fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audit Log DataTable Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary mb-3"><i class="fas fa-filter mr-1"></i> Filter Audit Records</h6>
                        
                        <!-- Date Range Inputs Controls -->
                        <div class="form-row align-items-center">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="sr-only" for="minDate">Start Date</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" id="minDate" class="form-control" placeholder="Start Date">
                                </div>
                            </div>

                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="sr-only" for="maxDate">End Date</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" id="maxDate" class="form-control" placeholder="End Date">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <button type="button" id="resetDateBtn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-undo mr-1"></i> Reset Range
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="auditHistoryTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Product Name</th>
                                        <th class="text-center">Virtual</th>
                                        <th class="text-center">Physical</th>
                                        <th class="text-center">Discrepancy</th>
                                        <th class="text-center">Expiry Status</th>
                                        <th class="text-center">Status</th>
                                        <th>Audited By</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $today = new DateTime();
                                    foreach ($logs as $index => $log): 
                                        $discrepancy = (int)$log['Discrepancy'];
                                        $statusClass = ($log['Status'] === 'Match') ? 'badge-success' : 'badge-danger';
                                        
                                        // Expiry badge logic
                                        $expiryBadge = '<span class="badge badge-light text-muted">N/A</span>';
                                        if (!empty($log['ExpiryDate']) && $log['ExpiryDate'] !== '0000-00-00') {
                                            $expDate = new DateTime($log['ExpiryDate']);
                                            $daysRemaining = (int)$today->diff($expDate)->format('%r%a');

                                            if ($daysRemaining < 0) {
                                                $expiryBadge = '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Expired</span>';
                                            } elseif ($daysRemaining <= 30) {
                                                $expiryBadge = '<span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> Expiring Soon</span>';
                                            } else {
                                                $expiryBadge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Valid</span>';
                                            }
                                        }

                                        // Format ISO Date YYYY-MM-DD for DataTables data-order
                                        $rawAuditDate = date('Y-m-d', strtotime($log['AuditDate']));
                                    ?>
                                      <tr>
                                          <td class="align-middle text-center font-weight-bold"></td>

                                          <td class="align-middle font-weight-bold text-gray-800">
                                              <?= htmlspecialchars($log['ProductName']) ?>
                                          </td>
                                          <td class="align-middle text-center"><?= (int)$log['VirtualStock'] ?></td>
                                          <td class="align-middle text-center font-weight-bold"><?= (int)$log['PhysicalStock'] ?></td>
                                          <td class="align-middle text-center font-weight-bold">
                                              <?php if ($discrepancy === 0): ?>
                                                  <span class="text-muted">0</span>
                                              <?php elseif ($discrepancy > 0): ?>
                                                  <span class="text-warning">+<?= $discrepancy ?> (Over)</span>
                                              <?php else: ?>
                                                  <span class="text-danger"><?= $discrepancy ?> (Deficit)</span>
                                              <?php endif; ?>
                                          </td>
                                          <td class="align-middle text-center">
                                              <?= $expiryBadge ?>
                                          </td>
                                          <td class="align-middle text-center">
                                              <span class="badge <?= $statusClass ?> px-3 py-2">
                                                  <?= htmlspecialchars($log['Status']) ?>
                                              </span>
                                          </td>
                                          <td class="align-middle">
                                              <small class="font-weight-bold"><?= htmlspecialchars($log['Fullname'] ?? 'N/A') ?></small>
                                          </td>
                                          <!-- ISO Date in data-order allows precise JavaScript string comparison -->
                                          <td class="align-middle" data-order="<?= $rawAuditDate ?>">
                                              <small class="text-muted"><?= date('M d, Y h:i A', strtotime($log['AuditDate'])) ?></small>
                                          </td>
                                      </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

<?php require 'partials/footer.php'; ?>

<script>
  $(document).ready(function() {

      // 1. Register Custom DataTables Date Range Filter Extension
      $.fn.dataTable.ext.search.push(
          function(settings, data, dataIndex) {
              let min = $('#minDate').val(); // Format: YYYY-MM-DD
              let max = $('#maxDate').val(); // Format: YYYY-MM-DD

              // Prevent error if table is not fully initialized yet
              if (typeof table === 'undefined' || !table) return true;

              // Extract data-order (raw date YYYY-MM-DD) from column index 8
              let rowDate = table.cell(dataIndex, 8).nodes().to$().attr('data-order');

              // Show all records if date inputs are empty
              if (!min && !max) return true;

              if (rowDate) {
                  if (min && !max) {
                      return rowDate >= min;
                  }
                  if (!min && max) {
                      return rowDate <= max;
                  }
                  if (min && max) {
                      return rowDate >= min && rowDate <= max;
                  }
              }

              return false;
          }
      );

      // 2. Initialize DataTable
      var table = $('#auditHistoryTable').DataTable({
          "destroy": true, // Prevents re-initialization errors
          "pageLength": 100,
          "columnDefs": [
              {
                  "searchable": false,
                  "orderable": false,
                  "targets": 0 // Row Serializer index
              }
          ],
          dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-right"B>>' +
               '<"row"<"col-md-12"tr>>' +
               '<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
          buttons: [
              {
                  extend: 'print',
                  text: '<i class="fas fa-print mr-1"></i> Print History',
                  className: 'btn btn-secondary btn-sm shadow-sm'
              },
              {
                  extend: 'excelHtml5',
                  text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                  className: 'btn btn-success btn-sm shadow-sm ml-1'
              },
              {
                  extend: 'pdfHtml5',
                  text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                  className: 'btn btn-danger btn-sm shadow-sm ml-1'
              }
          ]
      });

      // 3. Dynamic Row Serializer (Keeps 1, 2, 3... ordering intact when filtered)
      table.on('order.dt search.dt', function () {
          let i = 1;
          table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
              this.data(i++);
          });
      }).draw();

      // 4. Trigger DataTables re-draw on Date Input change
      $('#minDate, #maxDate').on('change keyup', function() {
          table.draw();
      });

      // 5. Reset Range Button Click Handler
      $('#resetDateBtn').on('click', function(e) {
          e.preventDefault();
          $('#minDate').val('');
          $('#maxDate').val('');
          table.draw();
      });
  });
</script>