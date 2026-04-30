<?php
require 'Database.php';
session_start();

// START output buffering to prevent invalid JSON
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_quantity') {

    header('Content-Type: application/json');

    try {
        // Validate input safely
        if (!isset($_POST['tid'], $_POST['new_qty'])) {
            throw new Exception('Invalid request data');
        }

        $tid = intval($_POST['tid']);
        $newQty = intval($_POST['new_qty']);

        if ($newQty < 1) {
            throw new Exception('Quantity must be at least 1');
        }

        // Get transaction
        $stmt = $db->conn->prepare("
            SELECT Price, Product, tDepartment, tCode 
            FROM transaction_tbl 
            WHERE TID = :tid AND Status = 'Not-Paid'
        ");
        $stmt->execute([':tid' => $tid]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            throw new Exception('Transaction not found or already paid');
        }

        // Check stock
        $stmtStock = $db->conn->prepare("
            SELECT Quantity 
            FROM supply_tbl 
            WHERE SupplyID = :product AND Department = :dept
        ");
        $stmtStock->execute([
            ':product' => $transaction['Product'],
            ':dept' => $transaction['tDepartment']
        ]);

        $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

        if (!$stock) {
            throw new Exception('Stock record not found');
        }

        if ($newQty > intval($stock['Quantity'])) {
            throw new Exception('Insufficient stock! Available: ' . $stock['Quantity']);
        }

        // Calculate amount
        $newAmount = $transaction['Price'] * $newQty;

        // Update
        $stmtUpdate = $db->conn->prepare("
            UPDATE transaction_tbl 
            SET qty = :qty, Amount = :amount 
            WHERE TID = :tid
        ");
        $stmtUpdate->execute([
            ':qty' => $newQty,
            ':amount' => $newAmount,
            ':tid' => $tid
        ]);

        // Get total
        $stmtTotal = $db->conn->prepare("
            SELECT SUM(Amount) as total 
            FROM transaction_tbl 
            WHERE tCode = :tCode AND Status = 'Not-Paid'
        ");
        $stmtTotal->execute([':tCode' => $transaction['tCode']]);

        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

        // CLEAN buffer before output
        ob_clean();

        echo json_encode([
            'status' => true,
            'new_amount' => $newAmount,
            'new_total' => $total['total'] ?? 0
        ]);
        exit;

    } catch (Exception $e) {

        // CLEAN buffer before output
        ob_clean();

        echo json_encode([
            'status' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}
?>


<?php if (!isset($_POST['action'])): ?>
<style>
  @media print {
    .page-break {
      page-break-after: always;
    }

    body {
      margin: 0;
      padding: 0;
    }

    #contentToPrint {
      font-family: Arial, sans-serif;
      font-size: 10px;
      line-height: 1.2;
      width: 75mm ; 
      white-space: nowrap; 
      overflow: hidden; 
    }

    #contentToPrint table {
      width: 100%;
      border-collapse: collapse;
    }

    #contentToPrint table th,
    #contentToPrint table td {
      font-size: 10px;
      text-align: left;
      white-space: nowrap; 
      word-wrap: break-word;
    }
  }

  .page-break {
    page-break-after: always;
  }
  
  .quantity-input {
    width: 70px;
    padding: 5px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 3px;
    background-color: #fff !important;
    display: inline-block !important;
  }
  
  .quantity-input:focus {
    outline: none;
    border-color: #4CAF50;
  }
  
  .quantity-input:enabled {
    background-color: #fff;
    cursor: text;
  }
  
  .quantity-input:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
  }
  
  .btn-warning {
    background-color: #ffc107;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
  }
  
  .btn-warning:hover {
    background-color: #e0a800;
  }
  
  .btn-danger {
    background-color: #dc3545;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    color: white;
  }
  
  .btn-danger:hover {
    background-color: #c82333;
  }
  
  .btn-dark {
    background-color: #343a40;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    color: white;
  }
  
  .btn-dark:hover {
    background-color: #23272b;
  }
  
  .updating-row {
    opacity: 0.6;
    background-color: #fff3cd;
  }
  
  .table-responsive {
    overflow-x: auto;
  }
  
  .transaction-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .transaction-table th,
  .transaction-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
</style>
<?php endif; ?>


<?php


// Regular request to display the table
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tcode'])) {
    $tCode = htmlspecialchars($_POST['tcode']);    

    $sql = 'SELECT Customer AS pCustomer, ProductName, TID, tCode, transaction_tbl.Price AS Price, qty, Amount, transaction_tbl.Status AS TStatus  
      FROM transaction_tbl 
      JOIN supply_tbl ON Product = supply_tbl.SupplyID 
      WHERE tCode = :tCode';

    $stmt = $db->checkExist($sql, [':tCode' => $tCode]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($products)): ?>
      <div class="table-responsive">
      <table class="transaction-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Description</th>
            <th>Price (&#x20A6)</th>
            <th>Qty</th>
            <th>Amount (&#x20A6)</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php $totalAmount = 0; ?>
        <?php foreach ($products as $i => $row): ?>
          <tr id="row_<?= $row['TID']; ?>" data-tid="<?= $row['TID']; ?>" data-price="<?= $row['Price']; ?>">
            <td><?= $i + 1; ?></td>
            <td><?= $row['ProductName']; ?></td>
            <td><?= number_format($row['Price'], 2); ?></td>
            <td class="qty-cell">
              <?php if ($row['TStatus'] == 'Not-Paid'): ?>
                <input type="number" 
                       id="qty_<?= $row['TID']; ?>"
                       class="quantity-input" 
                       value="<?= $row['qty']; ?>" 
                       min="1" 
                       data-tid="<?= $row['TID']; ?>"
                       data-old-qty="<?= $row['qty']; ?>"
                       style="background-color: #fff; border: 1px solid #ccc; width: 70px; padding: 5px;">
              <?php else: ?>
                <span><?= $row['qty']; ?></span>
              <?php endif; ?>
             </td>
            <td class="amount-cell" id="amount_<?= $row['TID']; ?>"><?= number_format($row['Amount'], 2); ?></td>
            <td>
                <?php if ($row['TStatus'] == 'Not-Paid'): ?>
                  <button type="button" onclick="deleteProduct(<?= $row['TID']; ?>)" class="btn btn-warning">Delete</button>
                <?php else: ?>
                    <?= $row['TStatus']; ?>
                <?php endif; ?>
             </td>
          </tr>
          <?php $totalAmount += $row['Amount']; ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">
              <?php 
              // Check if any product is not paid to show validate button
              $hasNotPaid = false;
              foreach ($products as $row) {
                  if ($row['TStatus'] == 'Not-Paid') {
                      $hasNotPaid = true;
                      break;
                  }
              }
              if($hasNotPaid): ?>
                <input type="button" onclick="validateTransaction('<?= $tCode; ?>')" class="btn btn-danger" value="Validate" />
              <?php else: ?>
                <!-- <input id="btn2" class="btn btn-dark" type="button" value="Print Receipt" onclick="PrintDoc2()" /><i class="fas fa-print"></i> -->

                <button id="btnPrint" type="button" class="btn btn-info" onclick="PrintDoc2()"> <i class="fas fa-print"></i> Print Receipt </button>
              <?php endif; ?>
            </td>
            <td colspan="1"><strong>Total Amount:</strong> <?= number_format($totalAmount, 2, '.', ','); ?></td>
          </tr>
        </tfoot>
      </table>
      </div>

      <div id="not_paid" style="display: none;">
        <div id="contentToPrint">
          <?php
            $sql = 'SELECT `department_tbl`.`Department` AS store, qty, Amount, ProductName, Product, Customer, TID, tCode, transaction_tbl.Price AS Price, transaction_tbl.Status AS TStatus,
                           cash, transfer, pos
                    FROM transaction_tbl
                    JOIN supply_tbl ON Product = supply_tbl.SupplyID
                    JOIN `department_tbl` ON `transaction_tbl`.`tDepartment` = `department_tbl`.`deptID`
                    WHERE tCode = :tCode AND transaction_tbl.Status = "Paid"';

            $stmt = $db->checkExist($sql, [':tCode' => $tCode]);
            $productsPaid = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($productsPaid)): 
                // $storeName = "Your Store Name";
                // $phone = "08012345678";
                // $state = "Your State, Country";
          ?>
              <div id="printinvoice" style="page-break-after: always;">
                <table style="width:100%; text-align:left">
                  <tr>
                    <td colspan="2" style="text-align:center; background-color:white">
                      <strong style="margin: 0;"><?= $storeName ?></strong><br />
                      <strong><?= $phone ?></strong><br />
                      <strong style="font-size:8pt; margin: 0"><?= $state ?></strong><br />
                      <strong style="margin-bottom: 0;">BILLING RECEIPT</strong>
                      <br /> Customer's Copy
                     </td>
                  </tr>
                  <tr>
                    <td>TID:</td>
                    <td id="tid"><?= $tCode; ?></td>
                  </tr>
                  <tr>
                    <td>Customer:</td>
                    <td id="patient"><?= htmlspecialchars($productsPaid[0]['Customer']) ?></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table id="transactionTable" style="width: 100%;">
                        <thead>
                          <tr>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                                $totalAmountC = 0;
                                foreach($productsPaid as $row) :?>
                                <tr>
                                  <td><?= $row['ProductName'] ?></td>
                                  <td><?= $row['qty'] ?></td>
                                  <td><?= number_format($row['Price']) ?></td>
                                  <td><?= number_format($row['Amount']) ?></td>
                                </tr>
                              <?php $totalAmountC += $row['Amount'];
                              endforeach ?>
                                <tr>
                                  <td colspan="3"><strong>Total:</strong></td>
                                  <td colspan="1"><strong>&#8358;<?= number_format($totalAmountC, 2) ?></strong></td>
                                </tr>
                             <?php if(isset($productsPaid[0]['cash']) || isset($productsPaid[0]['transfer']) || isset($productsPaid[0]['pos'])): ?>
                              <tr>
                                <td colspan="4">
                                    <strong>Payment:</strong><br>
                                    Cash: ₦<?= number_format($productsPaid[0]['cash'] ?? 0, 2) ?> | 
                                    Transfer: ₦<?= number_format($productsPaid[0]['transfer'] ?? 0, 2) ?> | 
                                    POS: ₦<?= number_format($productsPaid[0]['pos'] ?? 0, 2) ?>
                                 </td>
                              </tr>
                             <?php endif; ?>
                        </tbody>
                      </table>
                      <div class="footer">
                          <p style="margin: 0;">Printed By: <?= $_SESSION['fname']?>&nbsp; |&nbsp; Date: <?= date('d-M-Y h:i:s') ?></p>
                          <p style="margin: 0;">Powered by: Tikvaah Tech Solutions</p>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            <?php endif ?>
        </div>
      </div>

      <script>
      // Bind change event to quantity inputs after the table is loaded
      $(document).ready(function() {
          $('.quantity-input').off('change').on('change', function() {
              updateQuantity(this);
          });
          console.log('Quantity inputs found:', $('.quantity-input').length);
      });
      
      function updateQuantity(element) {
          console.log('updateQuantity called', element);
          
          const newQty = parseInt($(element).val());
          const tid = $(element).data('tid');
          const oldQty = parseInt($(element).data('old-qty'));
          const row = $('#row_' + tid);
          const price = parseFloat(row.data('price'));
          
          console.log('New Qty:', newQty, 'TID:', tid, 'Old Qty:', oldQty);
          
          // Validate quantity
          if(isNaN(newQty) || newQty < 1) {
              $(element).val(oldQty);
              Swal.fire('Error', 'Quantity must be at least 1', 'error');
              return;
          }
          
          // Show loading state
          row.addClass('updating-row');
          $(element).prop('disabled', true);
          
          // Send AJAX request to update quantity
          $.ajax({
            //   url: window.location.href,
                url: 'model/fetchTransactions.table2.php',
              method: 'POST',
              data: {
                  action: 'update_quantity',
                  tid: tid,
                  new_qty: newQty
              },
              dataType: 'json',
              success: function(response) {
                  console.log('Response:', response);
                  
                  if(response.status) {
                      // Update the amount in the row
                      $('#amount_' + tid).text(parseFloat(response.new_amount).toLocaleString(undefined, {
                          minimumFractionDigits: 2,
                          maximumFractionDigits: 2
                      }));
                      
                      // Update the old quantity data attribute
                      $(element).data('old-qty', newQty);
                      $(element).attr('data-old-qty', newQty);
                      
                      // Update the total amount display
                      const totalAmountDisplay = $('tfoot td:last-child');
                      const newTotal = parseFloat(response.new_total).toLocaleString(undefined, {
                          minimumFractionDigits: 2,
                          maximumFractionDigits: 2
                      });
                      totalAmountDisplay.html('<strong>Total Amount:</strong> ' + newTotal);
                      
                      // Show success
                      Swal.fire({
                          icon: 'success',
                          title: 'Updated!',
                          text: 'Quantity updated successfully',
                          timer: 1500,
                          showConfirmButton: false
                      });
                      
                      // Highlight the updated row
                      row.css('backgroundColor', '#d4edda');
                      setTimeout(() => {
                          row.css('backgroundColor', '');
                      }, 1000);
                  } else {
                      // Revert the quantity on error
                      $(element).val(oldQty);
                      Swal.fire('Error', response.error || 'Error updating quantity', 'error');
                  }
              },
              error: function(xhr, status, error) {
                console.error('RAW RESPONSE:', xhr.responseText);
                $(element).val(oldQty);

                // Swal.fire('Error', 'Server returned invalid response. Check console.', 'error');
                Swal.fire('Error', xhr.responseText, 'error');
              },
              complete: function() {
                  row.removeClass('updating-row');
                  $(element).prop('disabled', false);
              }
          });
      }
      </script>

    <?php else: ?>
      <div class="alert alert-info">No products added yet. Please add products to continue.</div>
    <?php endif;
}
?>

<script>
  function PrintDoc2() {
    const content = document.getElementById('contentToPrint').innerHTML;
    if(!content || content.trim() === '') {
        Swal.fire('Error', 'No receipt data available to print', 'error');
        return;
    }
    
    const newWindow = window.open('', '_blank', 'left=300,top=100,width=1000,height=700,toolbar=0,scrollbars=0,status=0');

    newWindow.document.write(`
      <html>
      <head>
        <title>Print Preview</title>
        <style>
          body {
            font-family: Arial, sans-serif;
          }
          table {
            width: 100%;
            border-collapse: collapse;
          }
          th, td {
            border: 1px solid #000;
            text-align: left;
            padding: 5px;
          }
          .footer {
            text-align: center;
            margin-top: 20px;
          }
          @media print {
            body {
              margin: 0;
              padding: 10px;
            }
          }
        </style>
      </head>
        <body>
          ${content}
          <script>
            window.onload = function() {
              window.print();
              setTimeout(function() { window.close(); }, 1000);
            }
          <\/script>
        </body>
        </html>
    `);

    newWindow.document.close();
  }

  function refreshTransactionTable() {
    const tCode = $('input[name="tcode"]').val();
    console.log('Refreshing table for tCode:', tCode);
    
    $.ajax({
        url: 'model/fetchTransactions.table2.php',
        method: 'POST',
        data: { tcode: tCode },
        success: function(data) {
            $('.transaction_table').html(data);
            console.log('Table refreshed');
        },
        error: function(xhr, status, error) {
            console.error('Error refreshing table:', error);
        }
    });
  }

  function deleteProduct(transactionID) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        $.ajax({
            url: 'model/delete.transaction.php',
            method: 'POST',
            data: { tid: transactionID },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                  refreshTransactionTable();
                  Swal.fire('Deleted!', 'Transaction deleted successfully.', 'success');
                } else {
                  Swal.fire('Error!', response.errors ? response.errors.error : 'Unknown error', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
            }
        });
    }
  }

  async function validateTransaction(tCode) {
    // First, get the total amount
    let totalAmount = 0;
    await $.ajax({
        url: 'model/getTransactionTotal2.php',
        method: 'POST',
        data: { tcode: tCode },
        dataType: 'json',
        async: false,
        success: function(response) {
            if(response.status) {
                totalAmount = response.total;
            }
        }
    });
    
    const { value: formValues } = await Swal.fire({
      title: "Payment Method",
      html: `
        <div style="text-align: center; margin-bottom: 15px;">
          <strong>Total Amount: ₦${totalAmount.toLocaleString()}</strong>
        </div>
        <small id="totalamounterror" class="text-danger"></small>
        <input id="swal-input1" name="cash" type="number" class="swal2-input" placeholder="Cash: e.g 20,000" value="0">
        <input id="swal-input2" name="transfer" type="number" class="swal2-input" placeholder="Transfer: e.g 8,500" value="0">
        <input id="swal-input3" name="pos" type="number" class="swal2-input" placeholder="POS: e.g 500" value="0">
        <div style="margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
          <strong>Total Payment: ₦<span id="totalPayment">0</span></strong>
        </div>
      `,
      focusConfirm: false,
      didOpen: () => {
        const cashInput = document.getElementById('swal-input1');
        const transferInput = document.getElementById('swal-input2');
        const posInput = document.getElementById('swal-input3');
        const totalPaymentSpan = document.getElementById('totalPayment');
        
        const updateTotalPayment = () => {
          const cash = parseFloat(cashInput.value) || 0;
          const transfer = parseFloat(transferInput.value) || 0;
          const pos = parseFloat(posInput.value) || 0;
          const total = cash + transfer + pos;
          totalPaymentSpan.textContent = total.toLocaleString();
          
          if(total === totalAmount) {
            totalPaymentSpan.style.color = 'green';
          } else {
            totalPaymentSpan.style.color = 'red';
          }
        };
        
        cashInput.addEventListener('input', updateTotalPayment);
        transferInput.addEventListener('input', updateTotalPayment);
        posInput.addEventListener('input', updateTotalPayment);
      },
      preConfirm: () => {
        const cash = parseFloat(document.getElementById("swal-input1").value) || 0;
        const transfer = parseFloat(document.getElementById("swal-input2").value) || 0;
        const pos = parseFloat(document.getElementById("swal-input3").value) || 0;
        const totalPaid = cash + transfer + pos;
        
        if(totalPaid !== totalAmount) {
          Swal.showValidationMessage(`Total payment (₦${totalPaid.toLocaleString()}) does not match transaction total (₦${totalAmount.toLocaleString()})`);
          return false;
        }
        
        if(totalPaid === 0) {
          Swal.showValidationMessage(`Please enter a payment amount`);
          return false;
        }
        
        return [cash, transfer, pos];
      }
    });

    if(formValues){
      $.ajax({
        url: 'model/validateTransaction2.php',
        method: 'POST',
        data: {
          tCode: tCode,
          cash: formValues[0],
          transfer: formValues[1],
          pos: formValues[2]
        },
        dataType: 'json',
        success: function(response) {
          if (response.status) {
              Swal.fire({
                  icon: 'success',
                  title: 'Transaction validated!',
                  text: response.message,
                  timer: 1500,
                  showConfirmButton: false
              });
              refreshTransactionTable();
              // Auto-print after validation
              setTimeout(() => {
                  PrintDoc2();
              }, 1500);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Validation failed',
              text: response.errors ? response.errors.error : response.message
            });
          }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });
        }
      });
    }
  }  
</script>