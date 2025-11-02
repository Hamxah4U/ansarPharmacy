<?php
		require 'partials/security.php';
    require 'partials/header.php';
?>

    <!-- Page Wrapper -->
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>

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

					<!-- Page Heading -->
				<div class="d-sm-flex align-items-center  mb-4">
					<h1 class="h3 mb-0 text-gray-800"></h1>
					<button class="btn btn-primary" type="button" data-toggle="modal" data-target="modelSupply"><strong>Inventory Report</strong></button>
				</div>

					<!-- Content Row -->
					<div class="table-responsive">
						<table class="table table-striped" id="supplyTable">
							<thead>
								<tr>
									<th>#</th>
									<!-- <th>Store</th> -->
									<th>Product</th>
									<th>Qty</th>
									<th>SupplyDate</th>
									<th>ExpiryDate</th>
									<th>RecordedBy</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
					<!-- Content Row -->
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- End of Main Content -->
<?php
  require 'partials/footer.php';
?>

<!-- Modal -->
<div class="modal fade" id="modelSupply" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
        <h5 class="modal-title text-primary"><strong>Product Window</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-danger">&times;</span>
        </button>
			</div>
			<div class="modal-body">
				<form id="formSupply">
          <input type="hidden" name="supplyID" id="supplyID">

					<div class="form-group">
						<label for="">Unit/Department</label>
						<select name="unit" class="form-control" id="unitID">
							<option value="--choose--">--choose--</option>
							<?php
									require 'model/Database.php';
									$stmt = $db->query('SELECT * FROM `department_tbl`');
									$units = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach($units as $unit): ?>
									<option value="<?= $unit['deptID']; ?>"><?= $unit['Department']; ?></option>
							<?php endforeach ?>
						</select>
						<small class="text-danger" id="errorUnit"></small>
					</div>

					<div class="form-group">
						<label for="">Product</label>
						<input class="form-control" type="text" id="productNameID" name="product" placeholder="Enter product name">
						<small class="text-danger" id="errorProduct"></small>
					</div>

          <div class="form-group">
						<label for="">Quantity</label>
						<input class="form-control" type="number" id="qty" name="qty" placeholder="Enter product quantity">
						<small class="text-danger" id="errorQty"></small>
					</div>

          <div class="form-group">
            <label for="ExpiryDate">ExpiryDate</label>
            <input type="date" name="ExpiryDate" id="ExpiryDate" class="form-control">
            <small class="text-danger" id="errorEx"></small>
          </div>

					<div class="form-group">
						<label for="my-input">Purchase Price (&#8358;)</label>
						<input id="purchasePrice" class="form-control" type="number" name="purchasePrice">
						<small class="text-danger" id="errorPPrice"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Selling Price (&#8358;)</label>
						<input class="form-control" type="number" name="price" id="priceID">
						<small class="text-danger" id="errorPrice"></small>
					</div>
					
					<button type="submit" class="btn btn-primary" id="action-btn" data-mode='add'><strong>Save</strong></button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
  const today = new Date();
	$('#supplyTable').DataTable({
		ajax: {
			url : 'model/inventory.table.php', //inventory.table.php
			dataSrc: '',
		},
		columns: [
			{ "data": null, render: (data, type, row, meta) => meta.row + 1 },
    //   { "data": "newdpt" },
			{ "data": "ProductName" },
			{ "data": "Quantity"},
			{ "data": "SupplyDate" },
      { "data": "ExpiryDate" },
			{ "data": "RecordedBy" },
		],

    createdRow: function (row, data) {
      const expiryDate = new Date(data.ExpiryDate);
      const threeMonthsFromNow = new Date(today);
      threeMonthsFromNow.setMonth(today.getMonth() + 3);

      if (expiryDate < today) {
        $(row).addClass('table-danger');
      } else if (expiryDate <= threeMonthsFromNow) {
        $(row).addClass('table-warning');
      }else{
        $(row).addClass('table-success');
      }
    }, 
	});
</script>