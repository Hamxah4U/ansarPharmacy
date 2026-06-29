<?php
    require 'partials/security.php';
    require 'partials/header.php';
    require 'model/Database.php';
?>

<style>
  /* Force Select2 to match Bootstrap 4 form-control elements perfectly */
.select2-container--bootstrap-4 .select2-selection--single {
    height: calc(1.5em + .75rem + 2px) !important;
    padding: 0.375rem 0.75rem !important; /* Standard BS4 padding */
    font-size: 1rem !important;
    font-weight: 400 !important;
    background-color: #fff !important;
    border: 1px solid #ced4da !important;
    border-radius: .25rem !important;
    display: flex;
    align-items: center; /* Let flexbox handle vertical centering cleanly */
}

/* Fix vertical centering alignment for select dropdown text */
.select2-container--bootstrap-4 .select2-selection--single .select2-selection__rendered {
    line-height: normal !important; /* Remove the massive forced line-height */
    padding-left: 0 !important;
    color: #495057 !important;
    width: 100%;
}

/* Match the drop arrow alignment */
.select2-container--bootstrap-4 .select2-selection--single .select2-selection__arrow {
    height: 100% !important; /* Let it scale to the container height */
    top: 0 !important;
    right: .75rem !important;
    display: flex;
    align-items: center;
}

/* Match focus shadow color effect from sb-admin template style */
.select2-container--bootstrap-4.select2-container--focus .select2-selection--single {
    border-color: #bac8f3 !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25) !important;
}
.select2-container {
    display: block !important;
    width: 100% !important;
}

.select2-dropdown {
    z-index: 1060 !important; 
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

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

<!-- Page Wrapper -->
<div id="wrapper">
  <!-- Sidebar -->
  <?php require 'partials/sidebar.php' ?>

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
      <!-- Topbar -->
      <?php require 'partials/nav.php'; ?>

      <!-- Begin Page Content -->
      <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-danger">Retails Dashboard</h1>
          <a href="/billing">
            <button class="btn btn-primary" type="button"><strong>Billing</strong></button>
          </a>
        </div>

        <!-- <button type="button" onclick="switchCamera()" class="btn btn-secondary mt-2">
                    Switch Camera
                </button> -->
        <!-- Transaction Header Form -->

        <div class="row mb-3">
          <div class="col-md-12 text-center">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <button type="button" id="btnUseScanner" class="btn btn-primary active"
                onclick="toggleInputMethod('scanner')">
                <i class="fas fa-camera"></i> Use Barcode/QR Scanner
              </button>
              <button type="button" id="btnUseManual" class="btn btn-secondary" onclick="toggleInputMethod('manual')">
                <i class="fas fa-keyboard"></i> Use Manual Product Selection
              </button>
            </div>
          </div>
        </div>

        <form id="transactionHeaderForm">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label><strong>Billing Code:</strong></label>
              <input value="<?= $tCode; ?>" readonly type="text" name="tcode" class="form-control" />
              <input type="hidden" name="nhisno" value="" />
            </div>

            <div class="form-group col-md-6">
              <label><strong>Customer's Name:</strong></label>
              <input name="customername" value="<?= $cname; ?>" type="text" class="form-control" id="customerName" />
              <small class="text-danger" id="errorName"></small>
            </div>
          </div>

          <div id="cameraSection" class="form-row justify-content-center mb-3">
            <div class="form-group col-md-6 text-center">
              <label><strong>Scan Barcode (Camera Active):</strong></label>
              <div id="reader"
                style="width:100%; max-width: 450px; margin: 0 auto; border: 1px solid #ddd; border-radius: 4px;"></div>
              <button type="button" class="btn btn-sm btn-info mt-2" onclick="switchCamera()"><i
                  class="fas fa-sync"></i> Switch Camera</button>
            </div>
          </div>
        </form>

        <!-- Product Selection Row -->
        <div id="manualEntrySection" style="display: none;">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label><strong>Store:</strong></label>
              <select name="dpt" class="form-control" id="storeSelect">
                <option value="--choose--">--choose--</option>
                <?php
                    $stmt = $db->query('SELECT * FROM `department_tbl`');
                    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($units as $unit):
                        $selected = ($unit['deptID'] == $dpt) ? 'selected' : '';
                    ?>
                <option value="<?= $unit['deptID'] ?>" <?= $selected ?>><?= $unit['Department'] ?></option>
                <?php endforeach ?>
              </select>
              <small id="errorDpt" class="text-danger"></small>
            </div>

            <div class="form-group col-md-6">
              <label><strong>Product:</strong></label>
              <select id="productSelect" class="form-control">
              </select>
              <small class="text-danger" id="errorService"></small>
            </div>
          </div>

          <div class="form-row mb-3">
            <div class="form-group col-md-3">
              <label><strong>Issued Qty:</strong></label>
              <input type="number" id="issuedqty" class="form-control" min="1">
              <small class="text-danger" id="errorQty"></small>
            </div>

            <div class="form-group col-md-3">
              <label><strong>Stock Qty:</strong></label>
              <input type="number" id="stockQty" class="form-control" readonly>
            </div>

            <div class="form-group col-md-3">
              <label><strong>Price (₦):</strong></label>
              <input type="text" id="price" class="form-control" readonly>
            </div>

            <div class="form-group col-md-3">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-success btn-block" onclick="addProductToTable()"><strong>Add Product
                  →</strong></button>
            </div>
          </div>
        </div>

        <!-- Transaction Table -->
        <div class="transaction_table"></div>

        <!-- Action Buttons - ONLY USE THIS ONE BLOCK -->
        <div class="form-row mt-3" id="actionButtons" style="display: none;">
          <div class="col-md-12">
            <!-- Validate Button -->
            <!-- <button id="btnValidate" type="button" class="btn btn-danger" onclick="validateTransaction()">Validate Transaction</button> -->

            <!-- Print Button (Hidden by default) -->
            <button id="btnPrint" type="button" class="btn btn-info" style="display: none;" onclick="PrintDoc2()">
              <i class="fas fa-print"></i> Print Receipt
            </button>
          </div>
        </div>

      </div>
    </div>
    <!-- End of Main Content -->
  </div>
</div>

<?php require 'partials/footer.php'; ?>

<style>
.transaction-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.transaction-table th,
.transaction-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.transaction-table th {
  background-color: #f2f2f2;
}

.delete-btn {
  color: white;
  background-color: #dc3545;
  border: none;
  padding: 5px 10px;
  border-radius: 3px;
  cursor: pointer;
}

.delete-btn:hover {
  background-color: #c82333;
}
</style>

<script>
$(document).ready(function() {
  // 1. Initialize Select2 on both elements cleanly
  $('#storeSelect').select2({
    theme: 'bootstrap-4',
    placeholder: '--choose--',
    allowClear: true
  });

  $('#productSelect').select2({
    theme: 'bootstrap-4',
    placeholder: '--choose--',
    allowClear: true
  });

  // Initialize table
  refreshTransactionTable();

  // Load products when store changes
  $('#storeSelect').on('change', function() {
    const deptID = $(this).val();
    if (deptID !== "--choose--" && deptID !== "" && deptID !== null) {
      $.ajax({
        url: "model/product.ajax.php",
        type: "POST",
        data: {
          department_id: deptID
        },
        success: function(response) {
          $("#productSelect").html(response).trigger('change');
        }
      });
    } else {
      $("#productSelect").html('<option value="">--choose--</option>').trigger('change');
    }
    resetProductFields();
  });

  // Load product details when product is selected
  $('#productSelect').on('change', function() {
    const productID = $(this).val();
    if (productID !== "" && productID !== "--select product--" && productID !== null) {
      $.ajax({
        url: "model/price.ajax.php",
        type: "POST",
        data: {
          product_id: productID,
          department_id: $('#storeSelect').val()
        },
        dataType: 'json',
        success: function(response) {
          $("#price").val(response.price);
          $("#stockQty").val(response.quantity);
          $('#purchaseprice').val(response.purchaprice);
        },
        error: function() {
          $("#price").val('');
          $("#stockQty").val('');
        }
      });
    } else {
      resetProductFields();
    }
  });
}); // <--- END OF DOCUMENT READY FOR INITIALIZATION

// MOVE ALL GLOBAL FUNCTIONS OUTSIDE SO THE BUTTONS CAN SEE THEM
function resetProductFields() {
  $("#issuedqty").val('');
  $("#price").val('');
  $("#stockQty").val('');
  $("#errorQty").text('');
}

function addProductToTable() {
  const productID = $('#productSelect').val();
  const productName = $('#productSelect option:selected').text();
  const issuedQty = $('#issuedqty').val();
  const price = $('#price').val();
  const stockQty = $('#stockQty').val();
  const tcode = $('input[name="tcode"]').val();
  const department = $('#storeSelect').val();
  const customerName = $('#customerName').val();

  let hasError = false;

  if (!customerName.trim()) {
    $('#errorName').text('Customer name is required!');
    hasError = true;
  } else {
    $('#errorName').text('');
  }

  if (!department || department === '--choose--') {
    $('#errorDpt').text('Department is required!');
    hasError = true;
  } else {
    $('#errorDpt').text('');
  }

  if (!productID || productID === '' || productID === '--select product--') {
    $('#errorService').text('Product is required!');
    hasError = true;
  } else {
    $('#errorService').text('');
  }

  if (!issuedQty || issuedQty <= 0) {
    $('#errorQty').text('Valid quantity is required!');
    hasError = true;
  } else if (parseInt(issuedQty) > parseInt(stockQty)) {
    $('#errorQty').text('Insufficient stock!');
    hasError = true;
  } else {
    $('#errorQty').text('');
  }

  if (hasError) return;

  $.ajax({
    url: 'model/product.transac.php',
    dataType: 'json',
    type: 'POST',
    data: {
      tcode: tcode,
      customername: customerName,
      dpt: department,
      product: productID,
      cprice: price,
      qty: stockQty,
      issuedqty: issuedQty,
      purchaseprice: $('#purchaseprice').val() || 0,
      nhisno: ''
    },
    success: function(response) {
      if (response.status) {
        refreshTransactionTable();
        resetProductFields();
        $('#productSelect').val('').trigger('change'); // Updates Select2 display interface
        $('#actionButtons').show();
      } else {
        if (response.errors.customer) $('#errorName').text(response.errors.customer);
        if (response.errors.unit) $('#errorDpt').text(response.errors.unit);
        if (response.errors.product) $('#errorService').text(response.errors.product);
        if (response.errors.proExist) $('#errorService').text(response.errors.proExist);
        if (response.errors.issuedqty) $('#errorQty').text(response.errors.issuedqty);
        if (response.errors.issuedqty_) $('#errorQty').text(response.errors.issuedqty_);
        if (response.errors.outofStock) $('#errorQty').text(response.errors.outofStock);
      }
    },
    error: function(xhr, status, error) {
      alert('Error adding product: ' + error);
    }
  });
}

function refreshTransactionTable() {
  const tCode = $('input[name="tcode"]').val();
  const customerName = $('#customerName').val();
  const department = $('#storeSelect').val();

  $.ajax({
    url: 'model/fetchTransactions.table2.php',
    method: 'POST',
    data: {
      tcode: tCode,
      customername: customerName,
      department: department
    },
    success: function(data) {
      $('.transaction_table').html(data);
      if ($('.transaction-table tbody tr').length > 0) {
        $('#actionButtons').show();
      }
    }
  });
}

function deleteProduct(transactionID) {
  if (confirm('Are you sure you want to delete this item?')) {
    $.ajax({
      url: 'model/delete.transaction.php',
      method: 'POST',
      data: {
        tid: transactionID
      },
      dataType: 'json',
      success: function(response) {
        if (response.status) {
          refreshTransactionTable();
          toastr.success('Product removed successfully');
        } else {
          toastr.error('Error: ' + (response.errors ? response.errors.error : 'Unknown error'));
        }
      },
      error: function() {
        toastr.error('Something went wrong. Please try again.');
      }
    });
  }
}

function validateTransaction() {
  const tCode = $('input[name="tcode"]').val();

  Swal.fire({
    title: "Payment Method",
    html: `
            <small id="totalamounterror" class="text-danger"></small>
            <div class="form-group">
                <label>Cash (₦):</label>
                <input id="cashInput" type="number" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label>Transfer (₦):</label>
                <input id="transferInput" type="number" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label>POS (₦):</label>
                <input id="posInput" type="number" class="form-control" placeholder="0" value="0">
            </div>
            <div class="form-group">
                <label>Total Amount to Pay:</label>
                <input id="totalAmount" type="text" class="form-control" readonly>
            </div>
        `,
    showCancelButton: true,
    confirmButtonText: "Validate",
    cancelButtonText: "Cancel",
    preConfirm: () => {
      const cash = parseFloat(document.getElementById("cashInput").value) || 0;
      const transfer = parseFloat(document.getElementById("transferInput").value) || 0;
      const pos = parseFloat(document.getElementById("posInput").value) || 0;
      const totalPaid = cash + transfer + pos;
      const displayValue = document.getElementById("totalAmount").value;
      const expectedTotal = parseFloat(displayValue.replace(/[₦,]/g, '')) || 0;

      if (Math.round(totalPaid) !== Math.round(expectedTotal)) {
        Swal.showValidationMessage(
          `Total paid: ₦${totalPaid.toLocaleString()} | Expected: ₦${expectedTotal.toLocaleString()}`);
        return false;
      }
      return {
        cash: cash,
        transfer: transfer,
        pos: pos
      };
    },
    didOpen: () => {
      $.ajax({
        url: 'model/getTransactionTotal2.php',
        method: 'POST',
        data: {
          tcode: tCode
        },
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            const num = parseFloat(response.total);
            const formatted = num.toLocaleString(undefined, {
              minimumFractionDigits: 2
            });
            $('#totalAmount').val('₦' + formatted);
          }
        }
      });
    }
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: 'model/validateTransaction.php',
        method: 'POST',
        data: {
          tCode: tCode,
          cash: result.value.cash,
          transfer: result.value.transfer,
          pos: result.value.pos
        },
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: response.message,
              timer: 1500,
              showConfirmButton: false
            });
            refreshTransactionTable();
            $('#actionButtons').show();
            $('#btnValidate').hide();
            $('#btnPrint').show();
            setTimeout(() => {
              if (typeof PrintDoc2 === 'function') PrintDoc2();
            }, 1500);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Failed',
              text: response.message
            });
          }
        },
        error: function() {
          toastr.error('Connection error. Please try again.');
        }
      });
    }
  });
}
</script>


<script>
let html5QrCode;
let currentCameraId = null;
let isScanning = false;

// START CAMERA
function startScanner(cameraId = null) {

  html5QrCode = new Html5Qrcode("reader");

  Html5Qrcode.getCameras().then(devices => {

    if (devices.length === 0) {
      alert("No camera found");
      return;
    }

    // Pick back camera first
    if (!cameraId) {
      const backCam = devices.find(c => c.label.toLowerCase().includes("back"));
      cameraId = backCam ? backCam.id : devices[0].id;
    }

    currentCameraId = cameraId;

    html5QrCode.start(
      cameraId, {
        fps: 10,
        qrbox: {
          width: 250,
          height: 250
        }
      },
      onScanSuccess
    );

  }).catch(err => {
    console.error(err);
  });
}

// SWITCH CAMERA
function switchCamera() {

  if (!html5QrCode) return;

  Html5Qrcode.getCameras().then(devices => {

    if (devices.length < 2) {
      Swal.fire('Info', 'Only one camera available', 'info');
      return;
    }

    let index = devices.findIndex(c => c.id === currentCameraId);
    let next = (index + 1) % devices.length;

    html5QrCode.stop().then(() => {
      startScanner(devices[next].id);
    });

  });
}

// SCAN SUCCESS
function onScanSuccess(decodedText) {

  // prevent duplicate scans
  if (isScanning) return;
  isScanning = true;

  console.log("Scanned:", decodedText);

  fetchProductByBarcode(decodedText);

  // unlock after 1.5 seconds
  setTimeout(() => {
    isScanning = false;
  }, 1500);
}

// INIT
$(document).ready(function() {
  startScanner();
});
</script>

<script>
function fetchProductByBarcode(barcode) {

  $.ajax({
    url: 'model/fetchProductByBarcode.php',
    type: 'POST',
    data: {
      barcode: barcode
    },
    dataType: 'json',

    success: function(res) {

      if (res.status) {

        const data = res.data;

        // 🔊 SUCCESS SOUND
        document.getElementById('beepSuccess').play();

        // SET STORE
        $('#storeSelect').val(data.Department).trigger('change');

        // WAIT FOR PRODUCTS TO LOAD
        setTimeout(() => {

          // SELECT PRODUCT
          $('#productSelect').val(data.SupplyID).trigger('change');

          // FILL DATA
          $('#price').val(data.Price);
          $('#stockQty').val(data.StockQuantity);

          // AUTO QTY
          $('#issuedqty').val(1);

          // AUTO ADD
          setTimeout(() => {
            addProductToTable();
          }, 200);

        }, 500);

      } else {

        // 🔴 ERROR SOUND
        document.getElementById('beepError').play();

        Swal.fire({
          icon: 'error',
          title: 'Product Not Found',
          text: res.message
        });
      }
    },

    error: function() {

      document.getElementById('beepError').play();

      Swal.fire({
        icon: 'error',
        title: 'Server Error',
        text: 'Something went wrong'
      });
    }
  });
}
</script>

<script>
function toggleInputMethod(method) {
  if (method === 'scanner') {
    // Toggle Layout visibility
    $('#manualEntrySection').hide();
    $('#cameraSection').show();

    // Update button visual active states
    $('#btnUseScanner').addClass('btn-primary active').removeClass('btn-secondary');
    $('#btnUseManual').addClass('btn-secondary').removeClass('btn-primary active');

    // Boot camera scanner hardware if library engine exists
    if (typeof startScanner === 'function') {
      startScanner();
    }
  } else if (method === 'manual') {
    // Toggle Layout visibility
    $('#cameraSection').hide();
    $('#manualEntrySection').show();

    // Update button visual active states
    $('#btnUseManual').addClass('btn-primary active').removeClass('btn-secondary');
    $('#btnUseScanner').addClass('btn-secondary').removeClass('btn-primary active');

    // Kill active streaming hardware tracking tracks to clear camera indicators
    if (html5QrCode && typeof html5QrCode.stop === 'function' && isScanning) {
      html5QrCode.stop().then(() => {
        isScanning = false;
        console.log("Camera engine streaming paused safely.");
      }).catch(err => {
        console.error("Error pausing scanner stream: ", err);
      });
    }
  }
}
</script>

<script>
   

$('#productSelect').select2({
    theme: 'bootstrap-4',
    placeholder: '--choose--',
    allowClear: true,
    width: '100%',
    dropdownParent: $('#transactionHeaderForm')
});
</script>
<!-- audio sound -->
<audio id="beepSuccess" src="sound/wood_plank_flicks.ogg"></audio>
<audio id="beepError" src="sound/wood_plank_flicks.ogg"></audio>