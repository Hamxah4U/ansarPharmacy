
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
						<div class="row mb-4">
								<div class="col-md-12 col-lg-12">
										<div class="card shadow-sm border-left-primary h-100 py-2">
												<div class="card-body">
														<div class="row align-items-center no-gutters">
																<?php if($_SESSION['role'] == 'Admin'):?>  
																	<div class="col mr-2">
																			<div class="text-xs font-weight-bold text-uppercase mb-1">
																					<strong><?= $storeName ?> Current Capital</strong>
																			</div>
																			<div class="h2 mb-0 font-weight-bold text-primary">
																					<?= '&#8358;' . number_format($totalCapital, 2, '.', ',') ?>
																			</div>
																			
																			<div class="mt-1 text-muted text-xs">
																					<strong>As of <?= date('d M, Y') ?></strong>
																			</div>
																	</div>
																<?php else:?>
																	<strong><?= $storeName ?></strong>
																<?php endif; ?>	
																<div class="col-auto">
																		<i class="fas fa-money-bill-wave fa-2x text-primary"></i>
																</div>
														</div>
												</div>
										</div>
								</div>
						</div>    
					</div>       
	 </div>
        <!-- End of Main Content -->
<?php
    require 'partials/footer.php';
?>
