<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow fixed">
	<!-- Sidebar Toggle (Topbar) -->
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
			<i class="fa fa-bars"></i>
	</button>
	<?php
		$stmt = $db->conn->prepare("SELECT SUM(COALESCE(`Quantity` * `Pprice`)) AS `current_capital` FROM `supply_tbl` GROUP BY `Department`, `ProductName`, `ExpiryDate`,`SupplyDate`");
		$stmt->execute();
		$capitals = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$totalCapital = 0;
		foreach($capitals as $index => $capital){
			$totalCapital += $capital['current_capital'];
		}
	?>

	<!-- <?php //if($_SESSION['role'] == 'Admin'):?>  
		<button type="button" class="btn btn-primary"><strong><?php // $storeName.' ' ?>=>&nbsp; CURRENT CAPITAL <strong class="text-warning"><?= '&#8358;'.number_format($totalCapital, 2, '.', ',') ?></strong> </strong></button>
		<?php // else:?>

    <strong><?php //$storeName ?></strong>

	<?php // endif; ?> -->
	<!-- Topbar Navbar -->
	<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown no-arrow d-sm-none">
					<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-search fa-fw"></i>
					</a>

					<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
							aria-labelledby="searchDropdown">
							<form class="form-inline mr-auto w-100 navbar-search">
									<div class="input-group">
											<input type="text" class="form-control bg-light border-0 small"
													placeholder="Search for..." aria-label="Search"
													aria-describedby="basic-addon2">
											<div class="input-group-append">
													<button class="btn btn-primary" type="button">
															<i class="fas fa-search fa-sm"></i>
													</button>
											</div>
									</div>
							</form>
					</div>
			</li>

			<!-- Nav Item - Messages -->
			<li class="nav-item dropdown no-arrow mx-1">
					<a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-envelope fa-fw"></i>
							<!-- Counter - Messages -->
							<span class="badge badge-danger badge-counter"><!-- 7 --></span>
					</a>
			</li>

			<div class="topbar-divider d-none d-sm-block"></div>

			<!-- Nav Item - User Information -->
			<li class="nav-item dropdown no-arrow">
					<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['fname'] ?></span>
							<img class="img-profile rounded-circle"
									src="img/undraw_profile.svg">
					</a>
					<!-- Dropdown - User Information -->
					<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
							aria-labelledby="userDropdown">

							<a class="dropdown-item" href="/updateprofile">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
									Profile
							</a>

							<a class="dropdown-item" href="/changepassword">
									<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
									Settings
							</a>

							<a class="dropdown-item" href="/currentcapital">
									
									<i class="fas fa-money-bill-wave fa-sm fa-fw mr-2 text-gray-400"></i>
									Current Capital
							</a>

							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>
					</div>
			</li>
	</ul>
</nav>