<?php
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';

?>

<?php
  if (isset($_GET['id'])) {
      $deptID = $_GET['id'];

      $stmt = $db->query('SELECT wholesaleprice, SupplyDate, Pprice, department_tbl.Department AS dpt, supply_tbl.RecordedBy, supply_tbl.ExpiryDate, supply_tbl.Quantity, supply_tbl.SupplyID, supply_tbl.ProductName, supply_tbl.Price, supply_tbl.Department, supply_tbl.Status, supply_tbl.RecordedBy FROM supply_tbl INNER JOIN department_tbl ON supply_tbl.Department = department_tbl.deptID WHERE supply_tbl.Quantity > 0 AND supply_tbl.Department = "'.$deptID.'" GROUP BY supply_tbl.Department, ProductName, ExpiryDate ORDER BY ProductName ASC');
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // use $deptID to fetch department details
  }
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
					<!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
							<h1 class="h3 mb-0 text-gray-800"></h1>
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modelSupply"><strong>New Supply</strong></button>
							
					</div> -->

					<!-- Content Row -->
					<div class="table-responsive">
					<table class="table table-striped no-wrap" id="supplyTable" style="white-space: nowrap;">
						<thead>
							<tr>
								<th>#</th>
								<!-- <th>Store</th>  -->
								<th>Product</th>             
								<th>Purchase Cost(&#8358;)</th>
								<th>Retail Price(&#8358;)</th>
								<th>Wholesale Price(&#8358;)</th>
                <th>Qty</th>
				        <!-- <th>Action</th> -->
                <!-- <th>Status</th> -->
								<th>SupplyDate</th>
                <!-- <th>ExpiryDate</th> -->
								<th>RecordedBy</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
                  foreach ($products as $key => $product): ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $product['ProductName'] ?></td>
                    <td><?= $product['Pprice'] ?></td>
                    <td><?= $product['Price'] ?></td>
                    <td><?= $product['wholesaleprice'] ?></td>
                    <td><?= $product['Quantity'] ?></td>
                    <!-- <td><?php // $product['SupplyID'] ?></td> -->
                    <!-- <td><?php //$product['Status'] ?></td> -->
                   
                    <td><?= $product['SupplyDate'] ?></td>
                    <td><?= $product['RecordedBy'] ?></td>
                    
                  </tr>
              <?php endforeach ?>
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
          <input type="hidden" name="unit" id="unitID" value="7">



					<div class="form-group">
						<label for="">Product</label>
						<input class="form-control" type="text" id="productNameID" name="product" placeholder="Enter product name">
						<small class="text-danger" id="errorProduct"></small>
					</div>

					
          			<div class="form-group">
						<label for="">Quantity (Card/Tablet/Bottle)</label>
						<input class="form-control" type="number" id="qty" name="qty" placeholder="Enter total quantity">
						<small class="text-danger" id="errorQty"></small>
					</div>

					<div class="form-group">
						<label for="ExpiryDate">ExpiryDate</label>
						<input type="date" name="ExpiryDate" id="ExpiryDate" class="form-control" placeholder="Purchase price per (Card/Tablet/Bottle)">
						<small class="text-danger" id="errorEx"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Purchase Price (&#8358;)</label>
						<input id="purchasePrice" class="form-control" type="int" placeholder="Purchase price per (Card/Tablet/Bottle)" name="purchasePrice" require>
						<small class="text-danger" id="errorPPrice"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Retails Price (&#8358;)</label>
						<input class="form-control" type="int" name="price" id="priceID" placeholder="Retail price per (Card/Tablet/Bottle)" require>
						<small class="text-danger" id="errorPrice"></small>
					</div>

					<div class="form-group">
						<label for="my-input">Wholesale Price (&#8358;)</label>
						<input class="form-control" type="int" name="wholesale" id="wholesaleprice" placeholder="Wholesale price per (Card/Tablet/Bottle)" required>
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
	});
</script>