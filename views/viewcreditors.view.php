
<?php
  require 'partials/security.php';
  require 'partials/header.php';
  require 'model/Database.php';

  //$stmt = $db->conn->prepare("SELECT Customer,nhisno,  SUM(Credit) as TotalCredit FROM `transaction_tbl` WHERE `Status` = 'Credit' GROUP BY nhisno");
  $stmt = $db->conn->prepare("SELECT * from customers_tbl");
  $stmt->execute();
  $customers = $stmt->fetchAll();  

  $today = date('Y-m-d');
?>
<style>
    .table-danger td {
    background-color: #f8d7da !important;
    color: #721c24;
    font-weight: bold;
  }

  @keyframes blink {
    0%   { opacity: 1; }
    50%  { opacity: 0; }
    100% { opacity: 1; }
  }

  .blink {
    animation: blink 2s infinite;
    color: #d9534f;      /* red */
    font-weight: bold;
  }
</style>
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>
  <div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
			<?php	require 'partials/nav.php';?>
			<!-- Begin Page Content -->
			<div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
					<h1 class="h3 mb-0 text-gray-800"></h1>
					<button class="btn btn-primary" type="button" data-target="#modalUser" data-toggle="modal"><strong>Add Customer</strong></button>
				</div>
        <p><strong>List of Creditors</strong></p>

        <div class="btn-group" role="group">
          <label class="btn btn-success" for="zeroBalance">Customers with Zero Balance</label>&nbsp;
          <label class="btn btn-info" for="zeroBalance">Customers with Deposits</label>&nbsp;
          <label class="btn btn-danger" for="zeroBalance">Customers with Outstanding Balance</label>&nbsp;
          <label class="btn btn-warning" for="zeroBalance">Customers Who Have Never Purchased on Credit</label>
        </div>

        <br>
        <div class="table table-responsive">
          <table class="table table-striped text-nowrap" id="creditors">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <!-- <th>Email</th> -->
                <th>Address</th>
                <th>Days Overdue</th>
                <!-- <th>Credit Bal.(&#8358;)</th> -->
                <th>Status</th>
                <th style="text-align:center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach($customers as $index => $customer):?>
                <?php
                  $stmtOverdue = $db->conn->prepare("
                    SELECT COUNT(*) AS overdue
                    FROM transaction_tbl
                    WHERE 
                      CID = :cid
                      AND Status = 'Credit'
                      AND credit_returning_date < CURDATE()
                  ");
                  $stmtOverdue->execute([':cid' => $customer['id']]);
                  $overdueRow = $stmtOverdue->fetch(PDO::FETCH_ASSOC);

                  $isOverdue = $overdueRow['overdue'] > 0;

                  // days overdue calculation (if needed in future)
                  $stmtDays = $db->conn->prepare("
                    SELECT 
                      MAX(DATEDIFF(credit_returning_date, TransacDate)) AS days_overdue, TransacDate, credit_returning_date
                    FROM transaction_tbl
                    WHERE 
                      CID = :cid
                      AND Status = 'Credit'
                      AND credit_returning_date IS NOT NULL
                      AND TransacDate IS NOT NULL
                  ");
                  $stmtDays->execute([':cid' => $customer['id']]);
                  $rowDays = $stmtDays->fetch(PDO::FETCH_ASSOC);

                  $daysOverdue = max(0, (int)($rowDays['days_overdue'] ?? 0));
                ?>

                <tr class="<?= $isOverdue ? 'table-danger' : '' ?>">
                  <td><?= $index + 1 ?></td>
                  <td><?= $customer['Fullname'] ?></td>
                  <td><?= $customer['phone'] ?></td>
                  <!-- <td><?php // $customer['email'] ?></td> -->
                  <td><?= $customer['address'] ?></td>

                  <td class="text-center">
                    <?php if ($rowDays['credit_returning_date'] === $today): ?>
                      <span class="blink">⏳ Due Today</span>

                    <?php elseif ($daysOverdue > 0): ?>
                      <span class="text-danger">
                        <?= $daysOverdue ?> day<?= $daysOverdue > 1 ? 's' : '' ?>
                      </span>

                    <?php endif; ?>
                  </td>


                  <?php // number_format($customer['TotalCredit'], 2) ?>
                  <td>
                    <?php
                      // echo $customer['id'];
                      $stmt = $db->query("SELECT SUM(COALESCE(Amount, 0)) AS amt FROM transaction_tbl t WHERE t.creditstatus = 'settlement' AND t.CID = '".$customer['id']."' GROUP BY t.CID");
                      $rowsettlement = $stmt->fetch(PDO::FETCH_ASSOC);

                      $stmtcr = $db->query("SELECT SUM(COALESCE(Credit, 0)) AS amtcredit FROM transaction_tbl t WHERE t.Status = 'Credit' AND t.CID = '".$customer['id']."' GROUP BY t.CID");
                      $rowcr = $stmtcr->fetch(PDO::FETCH_ASSOC);

                      $settlementAmt = $rowsettlement['amt'] ?? 0;
                      $creditAmt = $rowcr['amtcredit'] ?? 0;
                      $bal = $settlementAmt - $creditAmt;

                      
                        if ($bal > 0) {
                            echo '<strong class="btn btn-info">Deposit</strong>';
                        } elseif ($bal < 0) {
                            echo '<strong class="btn btn-danger">unpaid</strong>';
                        } elseif ($settlementAmt == 0 && $creditAmt == 0) {
                            echo '<strong class="btn btn-warning">uncredit</strong>';
                        }elseif($bal == 0){
                          echo '<strong class="btn btn-success">Cleared</strong>';
                        }
                      
                    ?>
                  </td>
                  <td>
                    <a href="/paycredit?id=<?= $customer['id'] ?>"><button type="button" class="btn btn-dark">Fund</button></a>
                    <a href="/creditbilling?id=<?= $customer['id'] ?>"><button type="button" class="btn btn-secondary">Credit Bill</button></a>
                    <a href=""></a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
			</div>
		</div>
		<!-- End of Main Content -->

    <!-- Modal -->
<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-primary"><strong>Customer's Registration Window</strong></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" style="color: red;"><strong>&times;</strong></span>
					</button>
			</div>
			<div class="modal-body">
				<form id="userForm">
					<input type="hidden" name="userID" id="userID">
					<div class="form-group">
						<label for="my-input">Customer's Fullname</label>
						<input id="fname" class="form-control" type="text" name="fname">
						<small class="text-danger" id="errorFname"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Customer's Phone</label>
						<input id="phone" class="form-control" type="number" name="phone">
						<small class="text-danger" id="errorPhone"></small>
					</div>

          <div class="form-group">
            <label for="">Customer's Email</label>
            <input type="email" name="email" class="form-control">
            <small id="email" class="text-danger"></small>
          </div>

					<div class="form-group">
						<label for="my-input">Customer's Address</label>
							<textarea name="address" id="" class="form-control"></textarea>
						<small class="text-danger" id="address"></small>
					</div>
					<button type="submit" class="btn btn-primary" id="action-btn" data-mode='add'><strong>Save</strong></button>
				</form>
			</div>
		</div>
	</div>
</div>

<?php  require 'partials/footer.php'; ?>

<script>
  $(document).ready(function () {
    $('#userForm').on('submit', function (e) {
        e.preventDefault();
        $('.text-danger').text('');
        $.ajax({
            url: 'model/addcustomers.php',
            dataType: 'JSON',
            data: $(this).serialize(),
            type: 'POST',
            success: function (response) {
                if (!response.status) {
                    $('#errorFname').text(response.errors.fullname || '');                    
                    $('#errorPhone').text(response.errors.phone || '');
                    $('#address').text(response.errors.address || '');   
                    $('#email').text(response.errors.email || '');                
                } else {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.onmouseenter = Swal.stopTimer;
                      toast.onmouseleave = Swal.resumeTimer;
                    }
                  });
                  Toast.fire({
                    icon: "success",
                    title: response.success.message
                  });
                  //alert(response.success.message);
                  $('#userForm')[0].reset();
                  $('#modalUser').modal('hide');
                  setTimeout(function(){
                    location.reload();
                  },2000)
                }
            },
            error: function (xhr, status, error) {
              alert('Error: ' + xhr.status + ' - ' + error);
            }
        });
    });
});
</script>

<script>
  $(document).ready(function(){
    $('#creditors').dataTable();
  })
</script>
