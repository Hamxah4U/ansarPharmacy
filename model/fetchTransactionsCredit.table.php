<style>
  /* General Styles for Printing */
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
      width: 75mm ; /*  80mm; Set to XP-80 paper width */
      white-space: nowrap; /* Prevent content from wrapping */
      overflow: hidden; /* Prevent overflow from being visible */
    }

    #contentToPrint table {
      width: 95%;
      border-collapse: collapse;
    }

    #contentToPrint table th,
    #contentToPrint table td {
      font-size: 10px;
      /* padding: 3px; */
      text-align: left;
      white-space: nowrap; /* Ensure table cells don’t wrap */
      word-wrap: break-word;
    }
  }

  .page-break {
    page-break-after: always;
  }
</style>

<?php
require 'Database.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tcode'])) {
    $tCode = htmlspecialchars($_POST['tcode']);
    

    $sql = 'SELECT Credit, Customer AS pCustomer, ProductName, TID, tCode, transaction_tbl.Price AS Price, qty, Amount, transaction_tbl.Status AS TStatus  
            FROM transaction_tbl 
            JOIN supply_tbl ON Product = supply_tbl.SupplyID 
            WHERE tCode = :tCode';

    $stmt = $db->checkExist($sql, [':tCode' => $tCode]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($products)): ?>
      <div class="able-responsive">
      <table class="table table-striped mt-0 mb-0">
        <tr class="pt-0 pb-0">
          <th>#</th>
          <th>Service</th>
          <th>Qty</th>
          <th>Price (&#x20A6)</th>
          <th>Amount (&#x20A6)</th>
          <th></th>
        </tr>
        <?php $totalAmount = 0; ?>
        <?php foreach ($products as $i => $row): ?>
          <tr class="pt-0 pb-0">
            <td><?= $i + 1; ?></td>
            <td><?= $row['ProductName']; ?></td>
            <td><?= $row['qty']; ?></td>
            <td><?= $row['Price']; ?></td>
            <td><?= $row['Credit']; ?></td>
            <td>
                <?php if ($row['TStatus'] == 'Not-Paid'): ?>
                  <button type="button" onclick="deleteProduct(<?= $row['TID']; ?>)" class="btn btn-warning">Delete</button>
                <?php else: ?>
                    <?= $row['TStatus']; ?>
                <?php endif; ?>
            </td>
          </tr>
          <?php $totalAmount += $row['Credit']; ?>
        <?php endforeach; ?>
        <tr>
          <?php if($row['TStatus'] == 'Not-Paid'):?>
            <td><input type="submit" onclick="validateTransaction('<?= $row['tCode']; ?>')" class="btn btn-danger" name="validate" value="Validate" /></td>
          <?php elseif($row['TStatus'] == 'Credit'): ?>
          <td><input id="btn2" class="btn btn-dark" type="button" value="Print" onclick="PrintDoc2()" /></td>
          <?php endif; ?>
            <td colspan="3"><strong>Total Amount:</strong></td>
            <td><strong>&#x20A6; <?= number_format($totalAmount, 2, '.', ','); ?></strong></td>
        </tr>
      </table>
      </div>

      <div id="not_paid" style="display: none;">
        <div id="contentToPrint">
          <?php
            $sql = 'SELECT Credit, `department_tbl`.`Department` AS store, qty, Amount, ProductName, Product, Customer, ProductName, TID, tCode, transaction_tbl.Price AS Price, qty, Amount, transaction_tbl.Status AS TStatus 
              FROM transaction_tbl
              JOIN supply_tbl ON Product = supply_tbl.SupplyID
              JOIN `department_tbl` ON `transaction_tbl`.`tDepartment` = `department_tbl`.`deptID`
              WHERE tCode = :tCode AND transaction_tbl.Status = "Credit" ' ;

            $stmt = $db->checkExist($sql, [':tCode' => $tCode]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($products)): ?>

              <div id="printinvoice" style="page-break-after: always;">
                <table style="width:95%; text-align:left">
                <tr>
                  <td colspan="2" style="text-align:center; background-color:white">
                    <!-- <img src="../img/SHAFA FERTILIZER.jpg" style="height:70px; margin:0" /><br /> -->
                    <strong style="margin: 0;"><?= $storeName ?></strong><br />
                    <!-- <strong style="margin: 0;"><?php // $subhead ?></strong><br /> -->
                    <strong><?= $phone ?></strong><br />
                    <strong style="font-size:8pt; margin: 0"><?= $state ?></strong><br />
                    <strong style="margin-bottom: 0;">Credit RECEIPT</strong>
                    <br /> Customer's Copy
                  </td>
                </tr>
                <tr>
                  <td>TID:</td>
                  <td id="tid"><?= $tCode; ?></td>
                </tr>
                <tr>
                    <td>Customer:</td>
                    <td id="patient"><?= $row['pCustomer'] ?></td>
                </tr>
                <tr>
                  <td colspan="2">
                    <!-- <hr /> -->
                    <table id="transactionTable" style="width: 95%;">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Store</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                          <tbody>
                          <?php
                                $i = 1;
                                $totalAmountC = 0;
                                foreach($products as $p => $row) :?>
                              <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $row['ProductName'] ?></td>
                                <td><?= $row['store'] ?></td>                                
                                <td><?= $row['qty'] ?></td>
                                <td><?= number_format($row['Price']) ?></td>
                                <td><?= number_format($row['Credit']) ?></td>
                              </tr>
                              <?php endforeach ?>
                              <tr>
                                <td colspan="4"><strong>Total:</strong></td>
                                <td colspan="2"><strong>&#8358;<?= number_format($totalAmount, 2) ?></strong></td>
                            </tr>
                          
                          </tbody>
                      </table>
                      <div class="footer">
                          <!-- <hr /> -->
                          <p style="margin: 0; font-size:10pt">Printed By: <?= $_SESSION['fname']?>&nbsp; |&nbsp; Date: <?= date('D-M-Y h:i:s') ?></p>
                          <p style="margin: 0; font-size:10pt">Powered by: HID Tech +2348037856962</p>
                      </div>
                  </td>
                </tr>
                  </table>
              </div>

              <!-- store copy -->
              
              <div class="page-break"></div>
              <div id="printinvoice" style="page-break-after: always;">
                <table style="width:100%; text-align:left">
                <tr>
                  <td colspan="2" style="text-align:center; background-color:white">
                    <!-- <img src="../img/SHAFA FERTILIZER.jpg" style="height:70px; margin:0" /><br /> -->
                    <strong style="margin: 0;"><?= $storeName ?></strong><br />
                    <!-- <strong style="margin: 0;"><?php //$subhead ?></strong><br /> -->
                    <strong>+2348032987462 | +2347066646961 </strong><br />
                    <strong style="font-size:8pt; margin: 0"><?= $state ?></strong><br />
                    <strong style="margin-bottom: 0;">Credit RECEIPT</strong>
                    <br /> Store's Copy
                  </td>
                </tr>
                  <tr>
                    <td>TID:</td>
                    <td id="tid"><?= $tCode; ?></td>
                  </tr>
                  <tr>
                      <td>Customer:</td>
                      <td id="patient"><?= $row['Customer'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <!-- <hr /> -->
                      <table id="transactionTable" style="width: 95%;">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Product</th>
                              <th>Store</th>                              
                              <th>Qty</th>
                              <th>Price</th>
                              <th>Amount</th>
                            </tr>
                          </thead>
                            <tbody>
                            <?php
                                  $i = 1;
                                  $totalAmountC = 0;
                                  foreach($products as $p => $row) :?>
                                <tr>
                                  <td><?= $i++ ?></td>
                                  <td><?= $row['ProductName'] ?></td>
                                  <td><?= $row['store'] ?></td>
                                  <td><?= $row['qty'] ?></td>
                                  <td><?= number_format($row['Price']) ?></td>
                                  <td><?= number_format($row['Credit']) ?></td>
                                </tr>
                                <?php endforeach ?>
                                <tr>
                                  <td colspan="4"><strong>Total:</strong></td>
                                  <td colspan="2"><strong>&#8358;<?= number_format($totalAmount, 2) ?></strong></td>
                              </tr>
                            
                            </tbody>
                        </table>
                        <div class="footer">
                            <!-- <hr /> -->
                            <p style="margin: 0; font-size:10pt">Printed By: <?= $_SESSION['fname']?>&nbsp; |&nbsp; Date: <?= date('D-M-Y h:i:s') ?></p>
                            <p style="margin: 0; font-size: 10pt">Powered by: HID Tech +2348037856962</p>
                        </div>
                    </td>
                  </tr>
                </table>
              </div>         

            <?php endif ?>
        </div>
      </div>

    <?php endif;
}
?>
<script>
  function PrintDoc2() {
    const content = document.getElementById('contentToPrint').innerHTML;
    const newWindow = window.open('', '_blank', 'left=300,top=100,width=1000,height=700,toolbar=0,scrollbars=0,status=0');

    newWindow.document.write(`
      <html>
      <head>
        <title>Print Preview</title>
        <style>
          /* Include any styles required for the content */
          body {
            font-family: Arial, sans-serif;
          }
          table {
            width: 95%;
            border-collapse: collapse;
          }
          th, td {
            border: 1px solid #000;
            // padding: 6px;
            text-align: left;
          }
          .footer {
            text-align: center;
            margin-top: 20px;
          }
        </style>
      </head>
        <body>
          ${content}
        </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.onload = function () {
      newWindow.print();
    };
  }
</script>

<script>
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

  function deleteProduct(transactionID) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        $.ajax({
            url: 'model/delete.transaction.php',
            method: 'POST',
            data: { tid: transactionID },
            success: function(response) {
              response = JSON.parse(response);
                if(response.status) {
                  console.log(transactionID);
                  refreshTransactionTable();
                  //alert('Transaction deleted successfully.');
                }else {
                  alert('Error: ' + (response.errors ? response.errors.error : 'Unknown error'));
                  console.log(transactionID);
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            }
        });
    }
  }

  async function validateTransaction(tCode) {
    const { value: formValues } = await Swal.fire({
      title: "Payment Method",
      html: `<strong class="text-danger">Credit</strong>`,           
      focusConfirm: false,
      showCancelButton: true, 
      confirmButtonText: "Ok",
      cancelButtonText: "Cancel",
    });

    if (formValues) {
        $.ajax({
            url: 'model/validateTransactionCredit.php',
            method: 'POST',
            data: {
                tCode: tCode,
                cash: formValues[0],
                transfer: formValues[1],
                pos: formValues[2]
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaction validated!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    refreshTransactionTable();
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
