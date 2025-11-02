<?php
		require 'partials/security.php';
    require 'partials/header.php';
?>

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

					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
							<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modelProduct"><strong>Add Product</strong></button>

					</div>

					<!-- Content Row -->
					<table class="table table-striped" id="productTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Product</th>
								<th>Price(&#8358;) </th>
								<th>Unit/Department</th>
								<th>Status</th>
								<th>RecordedBy</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					<!-- Content Row -->

			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

<?php
  require 'partials/footer.php';
?>

<!-- Modal -->
<div class="modal fade" id="modelProduct" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
					<h5 class="modal-title text-primary"><strong>Product Window</strong></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true" class="text-danger">&times;</span>
							</button>
			</div>
			<div class="modal-body">
				<form id="formProduct">
					<input type="hidden" name="productID" id="productID">
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
						<label for="">Product/Service</label>
						<input class="form-control" type="text" id="productNameID" name="product" placeholder="Enter product name">
						<small class="text-danger" id="errorProduct"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Price(&#8358;)</label>
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
	$('#productTable').DataTable({
		ajax: {
			url : 'model/product.table.php',
			dataSrc: '',
		},
		columns: [
			{ "data": null, render: (data, type, row, meta) => meta.row + 1 },
			{ "data": "Productname" },
			{ "data": "Price"},
			{ "data": "Department"},
			{ "data": "Status" },
			{ "data": "RegisterBy" },
			{ 
				"data": null,
					"render": function (data, type, row) {
						return `<button type="button" data-id="${row.proID}" class="btn btn-info" id='editProduct' ><span class="fas fa-fw fa-edit"></span></button>`;
					}
			}
		]
	});
</script>

<script>
	function resetForm(){
		$('#formProduct')[0].reset();
		$('#productNameID').text('');
		$('#priceID').text('');
		//$('#unitID').val('--choose--');

		//$('#productID').text('');
		// $('#productID').val('--choose--');
		// $('#unitID').val('--choose--');
		// $('#productNameID').text('');
		// $('#priceID').text('');
		// $('#unitID').text('');
		$('#action-btn').removeClass('btn-info').addClass('btn-primary').text('save').data('mode', 'add');
	}

	$(document).ready(function(){
		$('#formProduct').on('submit', function(e){
			e.preventDefault();
			const mode = $('#action-btn').data('mode');
			const url = mode === 'edit' ? 'model/product.update.php' : 'model/product.form.php';
			const iconType = mode === 'edit' ? 'info' : 'success';
			$.ajax({
				url: url,//'model/product.form.php',
				dataType: 'JSON',
				data: $(this).serialize(),
				type: 'POST',
				success: function(response){
					if(response.status === false){
						$('#errorUnit').text(response.errors.unit || '');
						$('#errorProduct').text(response.errors.product || response.errors.productExist || '');
						$('#errorPrice').text(response.errors.price || '');
					}else{
						//alert('success');
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
							title: response.message || response.success,
						});
						$('#errorUnit').text('');
        		$('#errorProduct').text('');
       			$('#errorPrice').text('');
						$('#productTable').DataTable().ajax.reload();
						$('#modelProduct').modal('hide');
						$('#formProduct')[0].reset();
					}
				},
				error: function(xhr, status, error){
					alert('error:' + xhr + status + error);
				}
			});
		})


  $('body').on('click', '#editProduct', function(e){
		e.preventDefault();
		let id = $(this).data('id');//data-id="${row.proID}"
		$.ajax({
			url: `model/product.edit.php?PID=${id}`,
			type: 'GET',
			dataType: 'JSON',
			success: function(response){
				console.log(response.Department); 
				$('#productID').val(response.proID);
				$('#productNameID').val(response.Productname);
				$('#priceID').val(response.Price);
				$('#unitID').val(response.Department);
				$('#action-btn').removeClass('btn-primary').addClass('btn-info').text('Update').data('mode', 'edit');
				$('#modelProduct').modal('show');
				$('#modelProduct').on('hidden.bs.modal', function(){
					resetForm();
				});
			},
			error: function(xhr, status, error){
				console.log(error);
				alert('error' + error);
			}
		});
	});

	});
</script>