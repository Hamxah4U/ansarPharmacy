<?php
    require 'partials/security.php';
    require 'partials/header.php';
    require 'model/Database.php';
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

                <!-- Transaction Header Form -->
                <form id="transactionHeaderForm">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label><strong>Billing Code:</strong></label>
                            <input value="<?= $tCode; ?>" readonly type="text" name="tcode" class="form-control" />
                            <input type="hidden" name="nhisno" value="" />
                        </div>
                        <div class="form-group col-md-4">
                            <label><strong>Customer's Name:</strong></label>
                            <input name="customername" value="<?= $cname; ?>" type="text" class="form-control" id="customerName" />
                            <small class="text-danger" id="errorName"></small>
                        </div>
                        <div class="form-group col-md-4">
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
                    </div>
                </form>

                <!-- Product Selection Row -->
                <div class="form-row mb-3">
                    <div class="form-group col-md-4">
                        <label><strong>Product:</strong></label>
                        <select id="productSelect" class="form-control">
                            <option value="">--choose--</option>
                        </select>
                        <small class="text-danger" id="errorService"></small>
                    </div>
                    
                    <div class="form-group col-md-2">
                        <label><strong>Issued Qty:</strong></label>
                        <input type="number" id="issuedqty" class="form-control" min="1">
                        <small class="text-danger" id="errorQty"></small>
                    </div>

                    <div class="form-group col-md-2">
                        <label><strong>Stock Qty:</strong></label>
                        <input type="number" id="stockQty" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-2">
                        <label><strong>Price (₦):</strong></label>
                        <input type="text" id="price" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-success btn-block" onclick="addProductToTable()"><strong>Add Product →</strong></button>
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
    // Initialize table
    refreshTransactionTable();
    
    // Load products when store changes
    $('#storeSelect').on('change', function() {
        const deptID = $(this).val();
        if(deptID !== "--choose--" && deptID !== "") {
            $.ajax({
                url: "model/product.ajax.php",
                type: "POST",
                data: { department_id: deptID },
                success: function(response) {
                    $("#productSelect").html(response);
                }
            });
        } else {
            $("#productSelect").html('<option value="">--choose--</option>');
        }
        resetProductFields();
    });
    
    // Load product details when product is selected
    $('#productSelect').on('change', function() {
        const productID = $(this).val();
        if(productID !== "" && productID !== "--select product--") {
            $.ajax({
                url: "model/price.ajax.php",
                type: "POST",
                data: { product_id: productID, department_id: $('#storeSelect').val() },
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
});

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
    
    // Validate
    let hasError = false;
    
    if(!customerName.trim()) {
        $('#errorName').text('Customer name is required!');
        hasError = true;
    } else {
        $('#errorName').text('');
    }
    
    if(!department || department === '--choose--') {
        $('#errorDpt').text('Department is required!');
        hasError = true;
    } else {
        $('#errorDpt').text('');
    }
    
    if(!productID || productID === '' || productID === '--select product--') {
        $('#errorService').text('Product is required!');
        hasError = true;
    } else {
        $('#errorService').text('');
    }
    
    if(!issuedQty || issuedQty <= 0) {
        $('#errorQty').text('Valid quantity is required!');
        hasError = true;
    } else if(parseInt(issuedQty) > parseInt(stockQty)) {
        $('#errorQty').text('Insufficient stock!');
        hasError = true;
    } else {
        $('#errorQty').text('');
    }
    
    if(hasError) return;
    
    // Add product via AJAX
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
            if(response.status) {
                refreshTransactionTable();
                resetProductFields();
                $('#productSelect').val('');
                $('#actionButtons').show();
            } else {
                if(response.errors.customer) $('#errorName').text(response.errors.customer);
                if(response.errors.unit) $('#errorDpt').text(response.errors.unit);
                if(response.errors.product) $('#errorService').text(response.errors.product);
                if(response.errors.proExist) $('#errorService').text(response.errors.proExist);
                if(response.errors.issuedqty) $('#errorQty').text(response.errors.issuedqty);
                if(response.errors.issuedqty_) $('#errorQty').text(response.errors.issuedqty_);
                if(response.errors.outofStock) $('#errorQty').text(response.errors.outofStock);
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
            // Check if table has rows, show validate button if yes
            if($('.transaction-table tbody tr').length > 0) {
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
            data: { tid: transactionID },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
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
            // 1. Get values and default to 0 if empty
            const cash = parseFloat(document.getElementById("cashInput").value) || 0;
            const transfer = parseFloat(document.getElementById("transferInput").value) || 0;
            const pos = parseFloat(document.getElementById("posInput").value) || 0;
            
            const totalPaid = cash + transfer + pos;

            // 2. STRIP symbols from the display field to get a real number
            const displayValue = document.getElementById("totalAmount").value;
            const expectedTotal = parseFloat(displayValue.replace(/[₦,]/g, '')) || 0;
            
            // 3. Compare using Math.round to avoid decimal math bugs
            if (Math.round(totalPaid) !== Math.round(expectedTotal)) {
                Swal.showValidationMessage(`Total paid: ₦${totalPaid.toLocaleString()} | Expected: ₦${expectedTotal.toLocaleString()}`);
                return false;
            }

            return { cash: cash, transfer: transfer, pos: pos };
        },
        didOpen: () => {
            // ONLY ONE didOpen function here
            $.ajax({
                url: 'model/getTransactionTotal2.php',
                method: 'POST',
                data: { tcode: tCode },
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        // Format the number for display
                        const num = parseFloat(response.total);
                        const formatted = num.toLocaleString(undefined, {minimumFractionDigits: 2});
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

                        // Force the container to be visible
                        $('#actionButtons').show();
                        // Hide Validate, Show Print
                        $('#btnValidate').hide();
                        $('#btnPrint').show();

                        setTimeout(() => {
                            if(typeof PrintDoc2 === 'function') PrintDoc2();
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