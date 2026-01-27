<?php
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';

		//$placeholder = '(Card/Tablet/Bottle)';
		$placeholder = '';
		//$placeholder2 = 'per (Card/Tablet/Bottle)';
		$placeholder2  = '';
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
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
							<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modelSupply"><strong>New Supply</strong></button>
							
					</div>

					<!-- Content Row -->
					<div class="table-responsive">
					<table class="table table-striped no-wrap" id="supplyTable" style="white-space: nowrap;">
						<thead>
							<tr>
								<th>#</th>
								<th>Store</th> 
								<th>Product</th>   
								             
								<th>Purchase Cost(&#8358;)</th>
								<th>Retail Price(&#8358;)</th>
								<th>Wholesale Price(&#8358;)</th>
                <th>Qty</th>
				<th>Action</th>
                <!-- <th>Status</th> -->
								<th>SupplyDate</th>
                <th>ExpiryDate</th>
								<th>RecordedBy</th>
								
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					</div>

			</div>

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
          <!-- <input type="hidden" name="unit" id="unitID" value="7"> -->


					<div class="form-group">
						<label for="">Store/Department</label>
						<select name="unit" class="form-control" id="unitID">
							<option value="--choose--">--choose--</option>
							<?php
									$stmt = $db->query('SELECT * FROM `department_tbl`');
									$units = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach($units as $unit): ?>
									<option value="<?php echo $unit['deptID']; ?>"><?php echo $unit['Department']; ?></option>
							<?php endforeach ?>
						</select>
						<small class="text-danger" id="errorUnit"></small>
					</div> 

					<div class="form-group">
						<label for="">Product</label>
						<input class="form-control" type="text" id="productNameID" name="product" placeholder="Enter product name">
						<small class="text-danger" id="errorProduct"></small>
					</div>

					<!-- <div class="form-group">
						<label for="">Pack</label>
						<input class="form-control" type="number" id="PackID" name="pack" placeholder="e.g 20" id="packID">
						<small class="text-danger" id="errorPack"></small>
					</div> -->

					<!-- <div class="form-group">
						<label for="">Tablet/card/</label>
						<input class="form-control" type="int" id="tabletID" name="unitpack" placeholder="e.g 500 pcs" required>
						<small class="text-danger" id="errorPack"></small>
					</div> -->

          			<div class="form-group">
						<label for="">Quantity <?= $placeholder ?></label>
						<input class="form-control" type="number" id="qty" name="qty" placeholder="Enter total quantity">
						<small class="text-danger" id="errorQty"></small>
					</div>

					<div class="form-group">
						<label for="ExpiryDate">ExpiryDate</label>
						<input type="date" name="ExpiryDate" id="ExpiryDate" class="form-control">
						<small class="text-danger" id="errorEx"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Purchase Price (&#8358;)</label>
						<input id="purchasePrice" class="form-control" type="int" placeholder="Purchase price <?= $placeholder2 ?>" name="purchasePrice" require>
						<small class="text-danger" id="errorPPrice"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Retails Price (&#8358;)</label>
						<input class="form-control" type="int" name="price" id="priceID" placeholder="Retail price <?= $placeholder2 ?>" require>
						<small class="text-danger" id="errorPrice"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Wholesale Price (&#8358;)</label>
						<input class="form-control" type="int" name="wholesale" id="wholesaleprice" placeholder="Wholesale price <?= $placeholder2 ?>" required>
						<small class="text-danger" id="errorWholesale"></small>
					</div>
					
					<button type="submit" class="btn btn-primary" id="action-btn" data-mode='add'><strong>Save</strong></button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('#supplyTable').DataTable({
		ajax: {
			url : 'model/supply.table.php',
			dataSrc: '',
		},
		columns: [
			{ "data": null, render: (data, type, row, meta) => meta.row + 1 },
			{ "data": "dpt" },
			{ "data": "ProductName" },
			    
			{ "data": "Pprice"},
			{ "data": "Price"},
			{ "data": "wholesaleprice"},
			{ "data": "Quantity"},
			{ 
				"data": null,
					"render": function (data, type, row) {
						return `<button type="button" data-id="${row.SupplyID}" class="btn btn-info" id='editSupply' ><span class="fas fa-fw fa-edit"></span></button>`;
					}
			},  
			// { "data": "Status" },
			{ "data": "SupplyDate" },
      		{ "data": "ExpiryDate" },
			{ "data": "RecordedBy" }
			
		]
	});
</script>

<script>
  function resetForm(){
    $('#formSupply')[0].reset();
    $('#unitID').val('--choose--');
    $('#productNameID').text();
    $('#qty').text();
    $('#ExpiryDate').text();
    $('#priceID').text();
    $('#action-btn').removeClass('btn-info').addClass('btn-primary').text('save').data('mode', 'add');
  }

  $(document).ready(function(){
    $('#formSupply').on('submit', function(e){
      e.preventDefault();
      const mode = $('#action-btn').data('mode');
      const url = mode === 'edit' ? 'model/supply.update.php' : 'model/supply.form.php';
      const iconType = mode === 'edit' ? 'info' : 'success';
      $.ajax({
        url: url,//'model/supply.form.php',
        data: $(this).serialize(),
        type: 'POST',
        dataType: 'JSON',
        success: function(response){
          if(response.status){
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
				icon: iconType,//"success",
				title: response.message.message,
			});
            $('#errorUnit').text('');
            $('#errorProduct').text('');
            $('#errorQty').text('');
            $('#errorPrice').text('');
            $('#errorEx').text('');
            $('#supplyTable').DataTable().ajax.reload();
            $('#modelSupply').modal('hide');
            $('#formSupply')[0].reset();
          }else{
            $('#errorUnit').text(response.errors.dpt || '');
            $('#errorProduct').text(response.errors.product || response.errors.productExist || '');
            $('#errorQty').text(response.errors.qty || '');
            $('#errorPrice').text(response.errors.price || response.errors.price_ || '');
            $('#errorEx').text(response.errors.ExpiryDate || '');
						$('#errorPPrice').text(response.errors.purchasePrice || '');
          }
        },
        error: function(xhr, status, error){
          alert('error:' + xhr + status + error);
        }
      });
    });
    
    $('body').on('click', '#editSupply', function(e){
      e.preventDefault();
      let id = $(this).data('id');
      $.ajax({
        url: `model/supply.edit.php?SID=${id}`,
        type: 'GET',
        dataType: 'JSON',
        success: function(response){
			//update
			$('#wholesaleprice').val(response.wholesaleprice);
			$('#PackID').val(response.pack);
			$('#tabletID').val(response.UnitPack);
			//update end
			$('#supplyID').val(response.SupplyID);
			$('#unitID').val(response.Department);
			$('#qty').val(response.Quantity);
			$('#productNameID').val(response.ProductName);
			$('#priceID').val(response.Price);
			$('#ExpiryDate').val(response.ExpiryDate);//purchasePrice
			$('#purchasePrice').val(response.Pprice);
			$('#action-btn').removeClass('btn-primary').addClass('btn-info').text('Update').data('mode', 'edit');
			$('#modelSupply').modal('show');
			$('#modelSupply').on('hidden.bs.modal', function(){
				resetForm();
			});
        },
        error: function(xhr, status, error){
          alert('Error');
        }
      });
    });

  });
</script>