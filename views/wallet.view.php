<?php
	require 'partials/security.php';
  require 'partials/header.php';
	// require 'model/Database.php';
	require 'classes/Users.class.php';
?>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'partials/sidebar.php' ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <?php
            require 'partials/nav.php';
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-md-12 col-lg-12">
                <div class="card shadow-sm border-left-primary h-100 py-2">
                    <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                    <?php if($_SESSION['role'] == 'Admin'):?>  
                                        <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        <strong>Wallet Balance</strong>
                                                </div>
                    <div class="h2 mb-0 font-weight-bold text-primary" id="walletBalance">
                        <?PHP 
                            $stmt = $db->query('SELECT COALESCE(SUM(`Amount`), 0) AS `totalTransaction` FROM `transaction_tbl` WHERE `Status` = "Paid"');
                            $total = $stmt->fetch(PDO::FETCH_ASSOC);
                            // echo number_format($total['totalTransaction'], '2', '.', ',');

                            $stmtWallet = $db->query('SELECT COALESCE(SUM(`amount`), 0) AS `totalWallet` FROM `wallet`');
                            $WalletTotal = $stmtWallet->fetch(PDO::FETCH_ASSOC);
                            $WalletTotal['totalWallet'];

                            $newBal = $total['totalTransaction'] - $WalletTotal['totalWallet'];

                            echo '₦' . number_format($newBal, 2, '.', ',');
                        ?>
                    </div>
                                                    
                        <div class="mt-1 text-muted text-xs">
                                <strong>As of <?= date('d M, Y') ?></strong>
                        </div>
                                            </div>
                                        <?php else:?>
                                            <strong><?= $storeName ?></strong>
                                        <?php endif; ?>	
                                        <div class="col-auto">
                                            <i class="fas fa-sync-alt fa-2x text-info"
                                                style="cursor:pointer"
                                                onclick="refreshWalletBalance()">
                                            </i>

                                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                                        </div>
                                </div>
                    </div>
                </div>
            </div>
        </div> 

                <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"></h1>
                <button class="btn btn-primary" type="button" data-target="#modalUser" data-toggle="modal"><strong>withdraw</strong></button>
            </div>

            <!-- Content Row -->
            <div class="table-responsive">
                <caption class="btn btn-block text-primary" style="color: blue;">Withdrawal History <span class="icofont-history"></span></caption>
                <table class="table table-striped text-nowrap" id="usersTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fullname</th>
                            <th>Amount</th>
                            <th>Date Withdraw</th>
                            <th>Time Withdraw</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
<?php

use PDO;
    require 'partials/footer.php';
?>

<!-- Modal -->
<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-primary"><strong>Withdrawa Window</strong></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" class="text-danger"><strong>&times;</strong></span>
					</button>
			</div>
			<div class="modal-body">
				<form id="userForm">
					<input type="hidden" name="userID" id="userID">
					<div class="form-group">
						<label for="my-input">Amount</label>
						<input id="amount" class="form-control" type="number" name="amount">
						<small class="text-danger" id="errorAmount"></small>
					</div>
					
					<div class="form-group">
						<label for="my-input">Reason</label>
						<textarea name="reason" id="reason" class="form-control"></textarea>
						<small class="text-danger" id="errorReason"></small>
					</div>

					
					<!-- <button id="action-btn" type="submit" class="btn btn-primary">Save</button> -->
					<button type="submit" class="btn btn-primary" id="action-btn" data-mode='add'><strong>Save</strong></button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>

    function refreshWalletBalance() {
        $.ajax({
            url: 'model/wallet.balance.php',
            dataType: 'json',
            success: function (res) {
                $('#walletBalance').html('₦' + res.balance);
            }
        });
    }

	$(document).ready(function(){
		$('#usersTable').DataTable({
			ajax:{
				url: 'model/wallet.table.php',
				dataSrc: '',
			},
			columns:[
				{'data': null, render:(data, type, row, meta) => meta.row + 1},
				{'data': 'Fullname'},
				{
                    data: 'amount',
                    render: function (data, type) {
                        return '₦' + Number(data).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                },
                {'data': 'datewithdraw'},
                {'data': 'timewithdrawa'},
                {'data': 'reason'},
			]
		});
	});
</script>

<script>
	function resetForm() {
        $('#userForm')[0].reset();
        $('#errorAmount, #errorReason').text('');
	}

	$(document).ready(function () {
        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            const mode = $('#action-btn').data('mode');
            //const url = 'model/wallet.form.php';
            $.ajax({
                url: 'model/wallet.form.php',
                dataType: 'JSON',
                data: $(this).serialize(),
                type: 'POST',
                success: function (response) {
                    if (response.status === false) {
                        $('#errorAmount').text(response.errors.amount || '');
                        $('#errorReason').text(response.errors.reason || '');
                    } else {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.success.message
                            });

                            $('#usersTable').DataTable().ajax.reload();
                             refreshWalletBalance();
                            $('#modalUser').modal('hide');
                            resetForm();
                    }
                },
                error: function (xhr, status, error) {
                        alert('Error: ' + xhr.status + ' - ' + error);
                }
            });
        });

        $('#modalUser').on('hidden.bs.modal', function () {
                resetForm();
        });
	});

</script>