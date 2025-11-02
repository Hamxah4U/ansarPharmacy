
<?php	
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';
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
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"></h1>
										<button type="button" data-target="#modelUnit" data-toggle="modal" class="btn btn-primary"><strong>Add Unit</strong></button>
                </div>
                <!-- Content Row -->
								 
                <div class="table-responsive">
								<table id="departmentTable" class="table table-striped" style="width: 100%;">
                  <thead>
                    <tr>
											<th>#</th>
											<th>Department</th>
											<th>Status</th>
											<th>RecordedBy</th>
											<th>Action</th>
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

<!-- Modal -->
<div class="modal fade" id="modelUnit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-primary"><strong>Unit/Department</strong></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" class="text-danger">&times;</span>
					</button>
			</div>
			<div class="modal-body">
				<form id="formUnit">
					<input type="hidden" id="unitId" name="unitId">
					<div class="form-group">
							<label for="Unit">Unit/Department</label>
							<input class="form-control" id="unitName" type="text" name="unit" placeholder="Enter Unit/Department">
							<small class="text-danger" id="errorUnit"></small>
					</div>
					<button type="submit" class="btn btn-primary" id="action-btn" data-mode="add">Save</button>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
    require 'partials/footer.php';
?>

<script>
	$(document).ready(function(){
		$('#departmentTable').DataTable({
			ajax:{
				url: 'model/unit.table.php',
				dataSrc: '',
			},
			columns: [
				{ "data": null, render: (data, type, row, meta) => meta.row + 1 },
				{ "data": "Department" },
        { "data": "Status" },
        { "data": "registerby" },
				{ "data": null,
					"render": function(data, type, row){
						return `<button class="btn btn-info" data-id="${row.deptID}" id='editDepartment'><span class="fas fa-fw fa-edit"></span></button>`;
					}
				}
			]

		});
	});
</script>

<script>
	function resetForm() {
		$('#formUnit')[0].reset(); 
		$('#unitId').val(''); 
		$('#errorUnit').text(''); 
		$('#action-btn').removeClass('btn-info').addClass('btn-primary').text('Save').data('mode', 'add'); 
  }

	$(document).ready(function(){
		$('#formUnit').on('submit', function(e){
			e.preventDefault();
			const mode = $('#action-btn').data('mode');
			const url = mode === 'edit' ? 'model/unit.update.php' : 'model/unit.form.php';
			const iconType = mode === 'edit' ? 'info' : 'success';
			$.ajax({
				//url: 'model/unit.form.php',
				url: url,//mode === 'edit' ? 'model/unit.edit.php' : 'model/unit.form.php', 
				dataType: 'JSON',
				data: $(this).serialize(),
				type: 'POST',
				success: function(response){
					if(response.status){
						//alert('success'+ response.message);
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
							title: response.message || response.success
						});
						$('#departmentTable').DataTable().ajax.reload();
						$('#modelUnit').modal('hide');
						resetForm();
					}else{
						$('#errorUnit').text(response.errors.unit || response.errors.unitExist || '');
					}
				},
				error: function(xhr, status, error){
					alert('Error:' + xhr + status + error);
				}
			});
		});

		$('body').on('click', '#editDepartment', function(e){
			e.preventDefault();
			let id = $(this).data('id');
			$.get(`model/unit.edit.php?deptId=${id}`, function(response){
				$('#unitId').val(response.deptID); // Set the department ID for update
				$('#unitName').val(response.Department);
				$('#action-btn').removeClass('btn-primary').addClass('btn-info').text('Update').data('mode', 'edit');
				$('#modelUnit').modal('show');
			}, 'json');
			
				$('#modelUnit').on('hidden.bs.modal', function () {
					resetForm();
				});

		});

	});
</script>
