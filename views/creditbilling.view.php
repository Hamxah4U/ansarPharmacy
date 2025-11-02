
<?php
		require 'partials/security.php';
    require 'partials/header.php';
    require 'model/Database.php';  

  if(isset($_GET['id'])){
    $id = $_GET['id'];
  }

    $stmtcustomer = $db->query("SELECT * FROM `customers_tbl` WHERE id = '$id'  ");
    $customer = $stmtcustomer->fetch(PDO::FETCH_ASSOC);
?> 
 
<?php
  function generateTransactionCode() {
    return date('ymd') . rand(100000000, 999999999);
  }

  if(!isset($_POST['tcode'])) {
    $tCode = generateTransactionCode();
    unset($_SESSION['customername']);
  } else {
    $tCode = $_POST['tcode'];
  }

  if (isset($_POST['customername'])) {
    $_SESSION['customername'] = $_POST['customername'];
  }

  if (isset($_POST['dpt'])) {
    $_SESSION['dpt'] = $_POST['dpt'];
  }

  $dpt = isset($_SESSION['dpt']) ? $_SESSION['dpt'] : "";
  $cname = isset($_SESSION['customername']) ? $_SESSION['customername'] : "";

?>

<script>
	function toggleDiv() {
    var paidDiv = document.getElementById("paid_div");
    var notPaidDiv = document.getElementById("not_paid_div");
    paidDiv.style.display = "none";
    notPaidDiv.style.display = "block";
	}
</script>


    <!-- Page Wrapper -->
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>

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
              <a href="/creditbilling">
              </a>
					</div>

					<!-- Content Row -->
					<form id="addTransaction">
            <input type="hidden" name="purchaseprice" id="Pprice">
            <input type="hidden" name="email" value="<?= $customer['email'] ?>">

            <div class="form-row">
              <div class="form-group col-md-6">
                <label><strong>Phone No.:</strong></label>
                <input  value="<?= $tCode; ?>"   type="hidden" name="tcode" class="form-control" />
                <input name="cid" type="hidden" value="<?= $customer['id'] ?>">
                <input readonly value="<?= $customer['phone'] ?>" placeholder="e.g 08012345678" type="text" name="nhisno" class="form-control" required />
              </div>
              <div class="form-group col-md-6">
                <label><strong>Customer's Name:</strong></label>
                <input readonly name="customername" value="<?= $customer['Fullname'] ?>" type="text" class="form-control" />
                <small class="text-danger" id="errorName"></small>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label><strong>Department:</strong></label>
                <select name="dpt" class="form-control" onchange="fetchServices(this.value)" id="unitdd">
                  <option value="--choose--">--choose--</option>
                  <?php
                      $stmt = $db->query('SELECT * FROM `department_tbl`');
                      $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      foreach($units as $unit):
                        $selected = ($unit['deptID'] == $dpt) ? 'selected' : '';
                      ?>
                      <option value="<?= $unit['deptID'] ?>"  $selected >  <?= $unit['Department'] ?> </option>
                  <?php endforeach ?>
                </select>
                <small id="errorDpt" class="text-danger"></small>
              </div>

              <div class="form-group col-md-6">
                <label><strong>Product:</strong></label>
                <select name="product" id="cproduct" class="form-control" onchange="fetchProductDetails(this.value)"> </select>
                <small class="text-danger" id="errorService"></small>
              </div>
            </div>

            <div class="form-row">
              
            <div class="form-group col-md-4">
                <label><strong>Issued Qty:</strong></label>
                <input type="number" id="issuedqty" name="issuedqty" class="form-control">
                <small class="text-danger" id="errorQty"></small>
              </div>

              <div class="form-group col-md-4">
                <label><strong>Stock Qty:</strong></label>
                <input type="number" id="qty" name="qty" class="form-control" readonly>
              </div>

              <div class="form-group col-md-4">
                <label><strong>Price (&#x20A6):</strong></label>
                <input type="text" id="price" name="cprice" class="form-control">
              </div>

            </div>

            <button type="submit" class="btn btn-primary mb-3"><strong>Add</strong></button>
          </form>
            
          <div id="paid_di" class="transaction_table"></div>

			</div>
		</div>
		<!-- End of Main Content -->

<?php
  require 'partials/footer.php';
?>

<script>
  $(document).ready(function(){
    $('#addTransaction').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: 'model/credit.php',
        dataType: 'JSON',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response){
          if(response.status){
            refreshTransactionTable();
            $('#issuedqty').val('');
            $('#productSearch').val('');
            $('.text-danger').text('');
          }else{
            $('#errorName').text(response.errors.customer || '');
            $("#errorDpt").text(response.errors.unit || '');
            $('#errorService').text(response.errors.product || response.errors.proExist || '');
            $('#errorQty').text(response.errors.issuedqty || response.errors.issuedqty_ || response.errors.outofStock || response.errors.notFound || '');
          }
        },
        error: function(xhr, status, error){
          //alert('error:' + xhr + status + error);
          console.log(xhr.responseText); 
          alert("Error: " + xhr.responseText);
        }
      });
    });

    function refreshTransactionTable() {
      const tCode = $('input[name="tcode"]').val();
      $.ajax({
        url: 'model/fetchTransactionsCredit.table.php',
        method: 'POST',
        data: { tcode: tCode },
        success: function(data) {
          $('.transaction_table').html(data);
        }
      });
    }
  });
</script>


<script>  
  /*  old dropdown o1*/
  
  function fetchServices(deptID) {
    if(deptID !== "") {
        $.ajax({
            url: "model/product.ajax.php",
            type: "POST",
            data: { department_id: deptID },
            success: function(response) {
              const $product = $("#cproduct");
              $("#cproduct").html(response);
              $product.addClass("form-control"); 
              $("#cproduct").select2({
                placeholder: "--select product--",
                allowClear: true
              });
            }
        });
    }else {
      $("#cproduct").html('<option value="">--select service--</option>');
      $("#cproduct").select2();
    }
  }

  function fetchProductDetails(productID) {
    if (productID !== "") {
      $.ajax({
          url: "model/price.ajax.php",
          type: "POST",
          data: { product_id: productID },
          success: function(response) {
              const data = JSON.parse(response);
              $("#price").val(data.price);
              $("#qty").val(data.quantity);
              $('#Pprice').val(data.purchaprice);
          }
      });
    }else {
      $("#price").val('');
      $("#qty").val('');
    }
  } 
</script>