
<?php
	require 'partials/security.php';
	require 'partials/header.php';
		require 'model/Database.php';
  
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
													<h1 class="h4 text-gray-900 mb-4"><strong><?= $storeName. ' ' ?>USER LOGIN</strong></h1>
													<img src="../img/ansar.png" alt="" style="width: 30%;">
												</div>
												<form id="loginForm">
													<div class="form-group">
															<label for="my-input">Email/Phone</label>
															<input id="my-input" class="form-control" type="text" name="email" placeholder="Enter Email or Phone">
															<small class="text-danger" id="emailError"></small>
													</div>

													<div class="form-group">
															<label for="">Password:</label>
															<input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="***********">
															<small class="text-danger" id="passError"></small>
													</div>

													<div class="d-flex justify-content-between align-items-center">
                						<button type="submit" class="btn btn-primary">Login</button>
                						<a href="/resetpassword" class="nav nav-link"><i class="text-danger">Forgot password?</i></a>
            							</div>													
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
		$('#loginForm').on('submit', function(e){
			e.preventDefault();
			$.ajax({
					url: 'model/user.login.php',
					dataType: 'JSON',
					data: $(this).serialize(),
					type: 'POST',
					success: function(response){
						if(response.status === false){
							//$('#emailError').text(response.errors.email || '');
							$('#passError').text(response.errors.password || '');
							const emailError = response.errors.emailPhone || response.errors.email || '';
							const passError = response.errors.invalidpass || response.errors.password || '';
							$('#passError').text(passError);
							$('#emailError').text(emailError);
						}else {
							//alert('Success: ' + response.success.message);
							const Toast = Swal.mixin({
								toast: true,
								position: "top-end",
								showConfirmButton: false,
								timer: 1000,
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
							$('#passError').text('');
							$('#emailError').text('');
							setTimeout(function(){
								window.location.href = response.success.redirect;
							}, 1000)

						}
					},
					error: function(xhr, status, error){
							alert('Error: ' + xhr);
					}
			});
		});
	});
</script>
</body>

</html>