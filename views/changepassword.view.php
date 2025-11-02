
<?php
		require 'partials/security.php';
		require 'partials/header.php';
?>
<div class="container">
		<!-- Outer Row -->
		<div class="row justify-content-center">

				<div class="col-xl-6 col-lg-12 col-md-9">

						<div class="card o-hidden border-0 shadow-lg my-5" style="border-radius: 12px;">
							<div class="card-body p-0">
									<!-- Nested Row within Card Body -->
									<div class="row">
										<div class="col-lg-2 p-3 p-lg-0"></div>
										<!-- Centered column with responsive padding adjustments -->
										<div class="col-lg-8 p-3 p-lg-0">
											<div class="p-5">
												<div class="text-center">
													<h1 class="h4 text-gray-900 mb-4"><strong>Change Password</strong></h1>
												</div>
												<form id="changePassword">
													
													<div class="form-group">
															<label for="">New Password:</label>
															<input type="password" class="form-control form-control-user" name="newPassword" id="exampleInputPassword" placeholder="***********">
															<small class="text-danger" id="newPassError"></small>
													</div>

													<div class="form-group">
															<label for="">Confirm Password:</label>
															<input type="password" class="form-control form-control-user" name="confirmPassword" id="exampleInputPassword" placeholder="***********">
															<small class="text-danger" id="confirmpassError"></small>
													</div>

													<button type="submit" class="btn btn-primary">Save</button>
												</form>
											</div>
										</div>
										<div class="col-lg-2 p-3 p-lg-0"></div>
								</div>
							</div>
						</div>

				</div>

		</div>

</div>

<?php
    require 'partials/footer.php';
?>

<script>
	$(document).ready(function(){
		$('#changePassword').on('submit', function(e){
			e.preventDefault();
			$.ajax({
				url: 'model/changepassword.php',
				data: $(this).serialize(),
				dataType: 'JSON',
				type: 'POST',
				success: function(response){
					if(!response.status){
						$('#newPassError').text(response.errors.newPassword || '');
						$('#confirmpassError').text(response.errors.confirmPassword || response.errors.passmismatch ||'');
					}else{
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
							icon: "info",
							title: response.success.message
						});
						$('#newPassError').text('');
						$('#confirmpassError').text('');
						setTimeout(function(){
							window.location.href = response.success.redirect
						}, 1000);
					}
				},
				error: function(xhr, status, error){
					alert('error' + xhr + status + error);
				}
			});
		});
	});
</script>

<script>
	// $(document).ready(function(){
	// 	$('#loginForm').on('submit', function(e){
	// 		e.preventDefault();
	// 		$.ajax({
	// 				url: 'model/user.login.php',
	// 				dataType: 'JSON',
	// 				data: $(this).serialize(),
	// 				type: 'POST',
	// 				success: function(response){
	// 					if(response.status === false){
	// 						//$('#emailError').text(response.errors.email || '');
	// 						$('#passError').text(response.errors.password || '');
	// 						const emailError = response.errors.emailPhone || response.errors.email || '';
	// 						const passError = response.errors.invalidpass || response.errors.password || '';
	// 						$('#passError').text(passError);
	// 						$('#emailError').text(emailError);
	// 					}else {
	// 						//alert('Success: ' + response.success.message);
	// 						const Toast = Swal.mixin({
	// 							toast: true,
	// 							position: "top-end",
	// 							showConfirmButton: false,
	// 							timer: 1000,
	// 							timerProgressBar: true,
	// 							didOpen: (toast) => {
	// 								toast.onmouseenter = Swal.stopTimer;
	// 								toast.onmouseleave = Swal.resumeTimer;
	// 							}
	// 						});
	// 						Toast.fire({
	// 							icon: "success",
	// 							title: response.success.message
	// 					}); 
	// 						$('#passError').text('');
	// 						$('#emailError').text('');
	// 						setTimeout(function(){
	// 							window.location.href = response.success.redirect;
	// 						}, 1000)

	// 					}
	// 				},
	// 				error: function(xhr, status, error){
	// 						alert('Error: ' + xhr);
	// 				}
	// 		});
	// 	});
	// });
</script>
