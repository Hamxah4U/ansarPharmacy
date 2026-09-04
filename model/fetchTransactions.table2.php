<?php
  require 'Database.php';
  session_start();

  // START output buffering to prevent invalid JSON
  ob_start();

  function formatQuantityFraction($rawQty, $pcsPerUnit = 1, $unitType = '', $unitKey = '', $typeCode = 'pcs') {
    if ($rawQty <= 0) return '0 pcs';

    $pcsPerUnit = max(1, (int)$pcsPerUnit);
    $unitTypeVal = strtolower((string)$unitType);
    $unitKeyVal  = strtolower((string)$unitKey);
    $isPieceType = ($unitTypeVal === '4' || $unitTypeVal === 'pc' || $unitKeyVal === 'pc');

    // If it's a Piece type, output raw pieces directly
    if ($isPieceType) {
        return (int)$rawQty . ' ' . ($typeCode ?: 'pcs');
    }

    // Calculate unit value (e.g., 12 pcs / 24 pcs per unit = 0.5)
    $qty = $rawQty / $pcsPerUnit;
    $whole = floor($qty);
    $fractional = $qty - $whole;
    $fractionStr = '';

    // Convert decimal remainders into fraction strings
    if (abs($fractional - 0.50) < 0.01) {
        $fractionStr = '½';
    } elseif (abs($fractional - 0.25) < 0.01) {
        $fractionStr = '¼';
    } elseif (abs($fractional - 0.75) < 0.01) {
        $fractionStr = '¾';
    } elseif ($fractional > 0) {
        $fractionStr = number_format($fractional, 2);
    }

    $formattedQty = '';
    if ($whole > 0) {
        $formattedQty = $fractionStr ? $whole . ' ' . $fractionStr : (string)$whole;
    } else {
        $formattedQty = $fractionStr ?: (string)$qty;
    }

    return trim($formattedQty . ' ' . ($typeCode ?: 'pcs'));
}

  $stmtUnits = $db->conn->query("SELECT id, unit_key, unit_label FROM unit_types_tbl ORDER BY id ASC");
  $allUnitTypes = $stmtUnits->fetchAll(PDO::FETCH_ASSOC);

  

  /* if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_quantity') {

      header('Content-Type: application/json');

      try {
          if (!isset($_POST['tid'], $_POST['new_qty'])) {
              throw new Exception('Invalid request data');
          }

          $tid = intval($_POST['tid']);
          $newQty = floatval($_POST['new_qty']); // Input count (e.g. 2 for 2 Cartons)

          if ($newQty <= 0) {
              throw new Exception('Quantity must be greater than 0');
          }

          // Fetch transaction details along with pcs_per_unit_v directly
          $stmt = $db->conn->prepare("
              SELECT Price, Product, tDepartment, tCode, pcs_per_unit_v 
              FROM transaction_tbl 
              WHERE TID = :tid AND Status = 'Not-Paid'
          ");
          $stmt->execute([':tid' => $tid]);
          $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$transaction) {
              throw new Exception('Transaction not found or already paid');
          }

          // Read pcs_per_unit_v directly from transaction_tbl
          $pcsPerUnit = max(1, intval($transaction['pcs_per_unit_v'] ?? 1));
          
          // Total pieces = user count * pieces per unit (e.g. 2 CTN * 24 = 48 pcs)
          $rawPieceQty = $newQty * $pcsPerUnit; 

          // Check stock available in supply_tbl
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

          if ($stock && $rawPieceQty > intval($stock['Quantity'])) {
              throw new Exception('Insufficient stock! Available: ' . $stock['Quantity'] . ' pcs');
          }

          // Amount = Price of selected unit * unit quantity entered
          $newAmount = $transaction['Price'] * $newQty; 

          // Update raw piece count and new amount
          $stmtUpdate = $db->conn->prepare("
              UPDATE transaction_tbl 
              SET qty = :qty, Amount = :amount 
              WHERE TID = :tid
          ");
          $stmtUpdate->execute([
              ':qty' => $rawPieceQty,
              ':amount' => $newAmount,
              ':tid' => $tid
          ]);

          // Recalculate transaction group total
          $stmtTotal = $db->conn->prepare("
              SELECT SUM(Amount) as total 
              FROM transaction_tbl 
              WHERE tCode = :tCode AND Status = 'Not-Paid'
          ");
          $stmtTotal->execute([':tCode' => $transaction['tCode']]);
          $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

          ob_clean();

          echo json_encode([
              'status' => true,
              'new_amount' => $newAmount,
              'new_total' => $total['total'] ?? 0
          ]);
          exit;

      } catch (Exception $e) {
          ob_clean();
          echo json_encode([
              'status' => false,
              'error' => $e->getMessage()
          ]);
          exit;
      }
  } */

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    header('Content-Type: application/json');

    try {
        if (!isset($_POST['tid'], $_POST['new_qty'])) {
            throw new Exception('Invalid request data');
        }

        $tid = intval($_POST['tid']);
        $inputQty = floatval($_POST['new_qty']); // The user-entered count (e.g., 1 carton or 4 pcs)

        if ($inputQty <= 0) {
            throw new Exception('Quantity must be greater than 0');
        }

        // Fetch transaction details along with unit_type and pcs_per_unit_v
        $stmt = $db->conn->prepare("
            SELECT Price, Product, tDepartment, tCode, pcs_per_unit_v, unit_type 
            FROM transaction_tbl 
            WHERE TID = :tid AND Status = 'Not-Paid'
        ");
        $stmt->execute([':tid' => $tid]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            throw new Exception('Transaction not found or already paid');
        }

        $pcsPerUnit = max(1, intval($transaction['pcs_per_unit_v'] ?? 1));
        $unitTypeVal = strtolower((string)($transaction['unit_type'] ?? ''));
        $isPieceType = ($unitTypeVal === '4' || $unitTypeVal === 'pc');

        if ($isPieceType) {
            $rawPieceQty = $inputQty; // If unit is pieces, input is directly raw pieces
        } else {
            $rawPieceQty = $inputQty * $pcsPerUnit; // e.g., 1 CTN * 48 = 48 pcs
        }

        // Check stock available in supply_tbl
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

        if ($stock && $rawPieceQty > intval($stock['Quantity'])) {
            throw new Exception('Insufficient stock! Available: ' . $stock['Quantity'] . ' pcs');
        }

        // Amount = Price of selected unit type * input quantity entered
        $newAmount = $transaction['Price'] * $inputQty; 

        // Update database with calculated raw piece count and amount
        $stmtUpdate = $db->conn->prepare("
            UPDATE transaction_tbl 
            SET qty = :qty, Amount = :amount 
            WHERE TID = :tid
        ");
        $stmtUpdate->execute([
            ':qty' => $rawPieceQty,
            ':amount' => $newAmount,
            ':tid' => $tid
        ]);

        // Recalculate group total
        $stmtTotal = $db->conn->prepare("
            SELECT SUM(Amount) as total 
            FROM transaction_tbl 
            WHERE tCode = :tCode AND Status = 'Not-Paid'
        ");
        $stmtTotal->execute([':tCode' => $transaction['tCode']]);
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

        ob_clean();
        echo json_encode([
            'status' => true,
            'new_amount' => $newAmount,
            'new_total' => $total['total'] ?? 0
        ]);
        exit;

    } catch (Exception $e) {
        ob_clean();
        echo json_encode([
            'status' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_price') {
      header('Content-Type: application/json');

      try {
          if (!isset($_POST['tid'], $_POST['new_price'])) {
              throw new Exception('Invalid request data');
          }

          $tid = intval($_POST['tid']);
          $newPrice = floatval($_POST['new_price']);

          if ($newPrice < 0) {
              throw new Exception('Price cannot be negative');
          }

          // Get current transaction
          $stmt = $db->conn->prepare("
              SELECT qty, tCode 
              FROM transaction_tbl 
              WHERE TID = :tid AND Status = 'Not-Paid'
          ");
          $stmt->execute([':tid' => $tid]);
          $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$transaction) {
              throw new Exception('Transaction not found or already paid');
          }

          // Recalculate amount using existing quantity
          $newAmount = $newPrice * intval($transaction['qty']);

          // Update Price and Amount in database
          $stmtUpdate = $db->conn->prepare("
              UPDATE transaction_tbl 
              SET Price = :price, Amount = :amount 
              WHERE TID = :tid
          ");
          $stmtUpdate->execute([
              ':price' => $newPrice,
              ':amount' => $newAmount,
              ':tid' => $tid
          ]);

          // Recalculate global total for unpaid items
          $stmtTotal = $db->conn->prepare("
              SELECT SUM(Amount) as total 
              FROM transaction_tbl 
              WHERE tCode = :tCode AND Status = 'Not-Paid'
          ");
          $stmtTotal->execute([':tCode' => $transaction['tCode']]);
          $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

          ob_clean();
          echo json_encode([
              'status' => true,
              'new_amount' => $newAmount,
              'new_total' => $total['total'] ?? 0
          ]);
          exit;

      } catch (Exception $e) {
          ob_clean();
          echo json_encode([
              'status' => false,
              'error' => $e->getMessage()
          ]);
          exit;
      }
  }

  

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_unit_type') {
    header('Content-Type: application/json');

    try {
        if (!isset($_POST['tid'], $_POST['new_unit_type'])) {
            throw new Exception('Invalid request data');
        }

        $tid = intval($_POST['tid']);
        $newUnitType = trim($_POST['new_unit_type']);

        // 1. Fetch transaction details and current unit_key
        $stmt = $db->conn->prepare("
            SELECT t.TID, t.Product, t.qty, t.tCode, u.unit_key 
            FROM transaction_tbl t
            LEFT JOIN unit_types_tbl u ON u.id = :unit_id OR u.unit_key = :unit_key_input
            WHERE t.TID = :tid AND t.Status = 'Not-Paid'
            LIMIT 1
        ");
        $stmt->execute([
            ':tid' => $tid,
            ':unit_id' => $newUnitType,
            ':unit_key_input' => $newUnitType
        ]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            throw new Exception('Transaction not found or already paid');
        }

        // 2. Fetch product prices from supply_tbl
        $stmtPrice = $db->conn->prepare("
            SELECT Price, Price AS full_price, half_price, quarter_price, pc_price 
            FROM supply_tbl 
            WHERE SupplyID = :product
        ");
        $stmtPrice->execute([':product' => $transaction['Product']]);
        $prices = $stmtPrice->fetch(PDO::FETCH_ASSOC);

        if (!$prices) {
            throw new Exception('Product pricing data not found');
        }

        // 3. Determine new price based on unit key
        $unitKey = strtolower($transaction['unit_key'] ?? $newUnitType);
        $newPrice = floatval($prices['full_price'] ?? $prices['Price']);

        if ($unitKey === 'half' && !empty($prices['half_price'])) {
            $newPrice = floatval($prices['half_price']);
        } elseif ($unitKey === 'quarter' && !empty($prices['quarter_price'])) {
            $newPrice = floatval($prices['quarter_price']);
        } elseif ($unitKey === 'pc' && !empty($prices['pc_price'])) {
            $newPrice = floatval($prices['pc_price']);
        }

        // 4. Calculate total amount for this row
        $qty = intval($transaction['qty']);
        $newAmount = $newPrice * $qty;

        // 5. Update transaction record with new unit, price, and amount
        $stmtUpdate = $db->conn->prepare("
            UPDATE transaction_tbl 
            SET unit_type = :unit_type, Price = :price, Amount = :amount 
            WHERE TID = :tid
        ");
        $stmtUpdate->execute([
            ':unit_type' => $newUnitType,
            ':price' => $newPrice,
            ':amount' => $newAmount,
            ':tid' => $tid
        ]);

        // 6. Calculate total for all unpaid items in transaction
        // 6. Calculate total for all unpaid items in transaction
        $stmtTotal = $db->conn->prepare("
            SELECT SUM(Amount) as total 
            FROM transaction_tbl 
            WHERE tCode = :tCode AND Status = 'Not-Paid'
        ");
        $stmtTotal->execute([':tCode' => $transaction['tCode']]);
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

        // 7. Fetch unit_type details (unit_key & type_code) to send back to JS
        $stmtUnitInfo = $db->conn->prepare("
            SELECT unit_key, type_code 
            FROM unit_types_tbl 
            WHERE id = :id OR unit_key = :key 
            LIMIT 1
        ");
        $stmtUnitInfo->execute([
            ':id' => $newUnitType, 
            ':key' => $newUnitType
        ]);
        $unitInfo = $stmtUnitInfo->fetch(PDO::FETCH_ASSOC);

        ob_clean();
        echo json_encode([
            'status' => true,
            'new_price' => $newPrice,
            'new_amount' => $newAmount,
            'new_total' => $total['total'] ?? 0,
            'type_code' => $unitInfo['type_code'] ?? 'pcs',
            'unit_key' => $unitInfo['unit_key'] ?? ''
        ]);
        exit;

    } catch (Exception $e) {
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
        $sql = 'SELECT 
            u.unit_key, 
            u.unit_label, 
            u.type_code, 
            pcs_per_unit_v AS pcsPerUnit, 
            Customer AS pCustomer, 
            ProductName, 
            TID, 
            tCode, 
            transaction_tbl.Price AS Price, 
            qty, 
            unit_type, 
            Amount, 
            transaction_tbl.Status AS TStatus  
        FROM transaction_tbl 
        JOIN supply_tbl ON Product = supply_tbl.SupplyID
        LEFT JOIN unit_types_tbl u ON u.id = transaction_tbl.unit_type OR u.unit_key = transaction_tbl.unit_type
        WHERE tCode = :tCode';

      $stmt = $db->checkExist($sql, [':tCode' => $tCode]);
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $hasNotPaid = false;

      foreach ($products as $pRow) {
          if ($pRow['TStatus'] == 'Not-Paid') {
              $hasNotPaid = true;
              break;
          }
      }

      if (!empty($products)): ?>
        <input type="text" id="transactionStatusFlag" value="<?= $hasNotPaid ? 'Not-Paid' : 'Paid'; ?>">
        <div class="table-responsive">
        <table class="transaction-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Price (&#x20A6)</th>
              <th>Qty</th>
              <th>Type</th>
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
              <td>
                <?php if ($row['TStatus'] == 'Not-Paid'): ?>
                  <input type="number" 
                        id="price_<?= $row['TID']; ?>"
                        class="quantity-input price-input" 
                        value="<?= $row['Price']; ?>" 
                        min="0"
                        step="0.01" 
                        data-tid="<?= $row['TID']; ?>"
                        data-old-price="<?= $row['Price']; ?>"
                        style="background-color: #fff; border: 1px solid #ccc; width: 85px; padding: 5px;">
                <?php else: ?>
                  <span><?= number_format($row['Price']); ?></span>
                <?php endif; ?>
              </td>
              <td class="qty-cell">
                <?php 
                  $pcsPerUnit = !empty($row['pcsPerUnit']) ? max(1, (int)$row['pcsPerUnit']) : 1;
                  $rawQty = (float)$row['qty']; 
                  
                  $unitTypeVal = strtolower((string)($row['unit_type'] ?? ''));
                  $unitKeyVal  = strtolower((string)($row['unit_key'] ?? ''));
                  $isPieceType = ($unitTypeVal === '4' || $unitTypeVal === 'pc' || $unitKeyVal === 'pc');

                  $displayQty = $isPieceType ? (int)$rawQty : ($pcsPerUnit > 0 ? ($rawQty / $pcsPerUnit) : $rawQty);
                ?>
                
                <?php if ($row['TStatus'] == 'Not-Paid'): ?>
                  <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="number" 
                          id="qty_<?= $row['TID']; ?>"
                          class="quantity-input" 
                          value="<?= $displayQty; ?>" 
                          min="0.01" 
                          step="any"
                          data-tid="<?= $row['TID']; ?>"
                          data-pcs-per-unit="<?= $pcsPerUnit; ?>"
                          data-old-qty="<?= $displayQty; ?>"
                          style="width: 65px; padding: 4px; text-align: center;">
                    
                    <small style="color: #555; font-weight: 500;">
                      <span id="type_code_label_<?= $row['TID']; ?>"><?= htmlspecialchars($row['type_code'] ?? 'pcs'); ?></span>
                      <span id="total_pcs_span_<?= $row['TID']; ?>" style="<?= $isPieceType ? 'display:none;' : ''; ?>">
                        (<?= (int)$rawQty; ?> pcs total)
                      </span>
                    </small>
                  </div>
                <?php else: ?>
                  <span><?= $displayQty; ?> <?= htmlspecialchars($row['type_code'] ?? 'pcs'); ?></span>
                <?php endif; ?>
              </td>
              <td class="unit-type-cell">
                <?php if ($row['TStatus'] == 'Not-Paid'): ?>
                  <select id="unit_type_<?= $row['TID']; ?>" 
                          class="form-control form-control-sm unit-type-select" 
                          data-tid="<?= $row['TID']; ?>" 
                          data-old-unit="<?= $row['id'] ?? ''; ?>"
                          style="width: 110px; padding: 3px 5px; height: 32px; background-color: #fff;">
                    <?php foreach ($allUnitTypes as $ut): ?>
                      <option value="<?= $ut['id']; ?>" <?= (isset($row['unit_type']) && $row['unit_type'] == $ut['id']) ? 'selected' : ''; ?>>
                        <?= $ut['unit_label']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php else: ?>
                  <span>
                    <?php 
                      $label = $row['unit_type'] ?? '';
                      foreach ($allUnitTypes as $ut) {
                        if ($ut['unit_key'] == $row['unit_type']) {
                          $label = $ut['unit_label'];
                          break;
                        }
                      }
                      echo htmlspecialchars($label);
                    ?>
                  </span>
                <?php endif; ?>
              </td>
              <td class="amount-cell" id="amount_<?= $row['TID']; ?>"><?= number_format($row['Amount']); ?></td>
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
                <?php if ($hasNotPaid): ?>
                  <input type="button" onclick="validateTransaction('<?= $tCode; ?>')" class="btn btn-danger" value="Validate" />
                <?php else: ?>
                  <button id="btnPrint" type="button" class="btn btn-info" onclick="PrintDoc2()"> <i class="fas fa-print"></i> Print Receipt </button>
                <?php endif; ?>
              </td>
              <td colspan="1"><strong>Total Amount:</strong> <?= number_format($totalAmount, 2, '.', ','); ?></td>
            </tr>
          </tfoot>

         <!--  <tfoot>
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
                  <button id="btnPrint" type="button" class="btn btn-info" onclick="PrintDoc2()"> <i class="fas fa-print"></i> Print Receipt </button>
                <?php endif; ?>
              </td>
              <td colspan="1"><strong>Total Amount:</strong> <?= number_format($totalAmount, 2, '.', ','); ?></td>
            </tr>
          </tfoot> -->
        </table>
        </div>

        <div id="not_paid" style="display: none;">
          <div id="contentToPrint">
            <?php
                $sql = 'SELECT 
                  `department_tbl`.`Department` AS store, 
                  transaction_tbl.qty, 
                  transaction_tbl.pcs_per_unit_v AS pcsPerUnit,
                  transaction_tbl.unit_type,
                  u.unit_key,
                  u.type_code,
                  Amount, 
                  ProductName, 
                  Product, 
                  Customer, 
                  TID, 
                  tCode, 
                  transaction_tbl.Price AS Price, 
                  transaction_tbl.Status AS TStatus,
                  cash, 
                  transfer, 
                  pos
                FROM transaction_tbl
                JOIN supply_tbl ON Product = supply_tbl.SupplyID
                JOIN `department_tbl` ON `transaction_tbl`.`tDepartment` = `department_tbl`.`deptID`
                LEFT JOIN unit_types_tbl u ON u.id = transaction_tbl.unit_type OR u.unit_key = transaction_tbl.unit_type
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
                                    <td>
                                      <?= formatQuantityFraction(
                                            $row['qty'], 
                                            $row['pcsPerUnit'] ?? 1, 
                                            $row['unit_type'] ?? '', 
                                            $row['unit_key'] ?? '', 
                                            $row['type_code'] ?? 'pcs'
                                          ); 
                                      ?>
                                    </td>
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
              const newQty = parseFloat($(element).val());
              const tid = $(element).data('tid');
              const oldQty = parseFloat($(element).data('old-qty'));
              const pcsPerUnit = parseFloat($(element).data('pcs-per-unit')) || 1;
              const row = $('#row_' + tid);

              if (isNaN(newQty) || newQty <= 0) {
                  $(element).val(oldQty);
                  Swal.fire('Error', 'Quantity must be greater than 0', 'error');
                  return;
              }

              row.addClass('updating-row');
              $(element).prop('disabled', true);

              $.ajax({
                  url: 'model/fetchTransactions.table2.php',
                  method: 'POST',
                  data: {
                      action: 'update_quantity',
                      tid: tid,
                      new_qty: newQty
                  },
                  dataType: 'json',
                  success: function(response) {
                      if (response.status) {
                          // 1. Update Amount & Total
                          $('#amount_' + tid).text(parseFloat(response.new_amount).toLocaleString(undefined, {
                              minimumFractionDigits: 2,
                              maximumFractionDigits: 2
                          }));

                          $(element).data('old-qty', newQty).attr('data-old-qty', newQty);

                          const newTotal = parseFloat(response.new_total).toLocaleString(undefined, {
                              minimumFractionDigits: 2,
                              maximumFractionDigits: 2
                          });
                          $('tfoot td:last-child').html('<strong>Total Amount:</strong> ' + newTotal);

                          // 2. Dynamically update total pieces calculation string
                          const calcTotalPcs = Math.round(newQty * pcsPerUnit);
                          $('#total_pcs_span_' + tid).text('(' + calcTotalPcs + ' pcs total)');

                          row.css('backgroundColor', '#d4edda');
                          setTimeout(() => row.css('backgroundColor', ''), 1000);
                      } else {
                          $(element).val(oldQty);
                          Swal.fire('Error', response.error || 'Error updating quantity', 'error');
                      }
                  },
                  error: function(xhr) {
                      $(element).val(oldQty);
                      Swal.fire('Error', xhr.responseText || 'Server communication error', 'error');
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

            // 1. Read payment status flag
            const status = $('#transactionStatusFlag').val();
            const hasRows = $('.transaction-table tbody tr').length > 0;

            // 2. Toggle button visibility based on payment status
            if (status === 'Paid') {
                $('#btnAddProduct').hide();
                $('#btnNewBilling').css('display', 'block').show();
            } else {
                $('#btnAddProduct').css('display', 'block').show();
                $('#btnNewBilling').hide();
            }

            // 3. Toggle bottom action buttons block
            if (hasRows) {
                $('#actionButtons').show();
            } else {
                $('#actionButtons').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error refreshing table:', error);
        }
    });
  }

  /*    function refreshTransactionTable() {
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
    } */

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
        <input id="swal-input1" name="cash" type="number" class="swal2-input" placeholder="Cash: e.g 20,000">
        <input id="swal-input2" name="transfer" type="number" class="swal2-input" placeholder="Transfer: e.g 8,500">
        <input id="swal-input3" name="pos" type="number" class="swal2-input" placeholder="POS: e.g 500">
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
              
              // REFRESH TABLE AFTER SUCCESSFUL VALIDATION IN DB
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
        }
      });
    }

    /* if(formValues){
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
    } */
  }  
</script>

<script>
    $(document).ready(function() {
      // Existing Qty Binding
      $('.quantity-input:not(.price-input)').off('change').on('change', function() {
          updateQuantity(this);
      });

      // New Price Binding
      $('.price-input').off('change').on('change', function() {
          updatePrice(this);
      });
  });

  function updatePrice(element) {
      const newPrice = parseFloat($(element).val());
      const tid = $(element).data('tid');
      const oldPrice = parseFloat($(element).data('old-price'));
      const row = $('#row_' + tid);

      if (isNaN(newPrice) || newPrice < 0) {
          $(element).val(oldPrice);
          Swal.fire('Error', 'Price must be 0 or greater', 'error');
          return;
      }

      row.addClass('updating-row');
      $(element).prop('disabled', true);

      $.ajax({
          url: 'model/fetchTransactions.table2.php',
          method: 'POST',
          data: {
              action: 'update_price',
              tid: tid,
              new_price: newPrice
          },
          dataType: 'json',
          success: function(response) {
              if (response.status) {
                  // Update dataset price on row for dynamic calculation
                  row.data('price', newPrice);

                  // Update row Amount text
                  $('#amount_' + tid).text(parseFloat(response.new_amount).toLocaleString(undefined, {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                  }));

                  // Update old-price dataset attribute
                  $(element).data('old-price', newPrice);
                  $(element).attr('data-old-price', newPrice);

                  // Update overall total display
                  const totalAmountDisplay = $('tfoot td:last-child');
                  const newTotal = parseFloat(response.new_total).toLocaleString(undefined, {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                  });
                  totalAmountDisplay.html('<strong>Total Amount:</strong> ' + newTotal);

                  // Success visual effect
                  row.css('backgroundColor', '#d4edda');
                  setTimeout(() => {
                      row.css('backgroundColor', '');
                  }, 1000);
              } else {
                  $(element).val(oldPrice);
                  Swal.fire('Error', response.error || 'Error updating price', 'error');
              }
          },
          error: function(xhr) {
              $(element).val(oldPrice);
              Swal.fire('Error', xhr.responseText || 'Server communication error', 'error');
          },
          complete: function() {
              row.removeClass('updating-row');
              $(element).prop('disabled', false);
          }
      });
  }
</script>

<script>
  $(document).ready(function() {
    // Existing bindings...
    $('.quantity-input:not(.price-input)').off('change').on('change', function() {
        updateQuantity(this);
    });

    $('.price-input').off('change').on('change', function() {
        updatePrice(this);
    });

    // New Unit Type Binding
    $('.unit-type-select').off('change').on('change', function() {
        updateUnitType(this);
    });
});

/* function updateUnitType(element) {
    const newUnitType = $(element).val();
    const tid = $(element).data('tid');
    const oldUnit = $(element).data('old-unit');
    const row = $('#row_' + tid);

    row.addClass('updating-row');
    $(element).prop('disabled', true);

    $.ajax({
        url: 'model/fetchTransactions.table2.php',
        method: 'POST',
        data: {
            action: 'update_unit_type',
            tid: tid,
            new_unit_type: newUnitType
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                // Update old-unit cache attribute
                $(element).data('old-unit', newUnitType);
                $(element).attr('data-old-unit', newUnitType);

                // Highlight row feedback
                row.css('backgroundColor', '#d4edda');
                setTimeout(() => {
                    row.css('backgroundColor', '');
                }, 1000);
            } else {
                $(element).val(oldUnit);
                Swal.fire('Error', response.error || 'Failed to update unit type', 'error');
            }
        },
        error: function(xhr) {
            $(element).val(oldUnit);
            Swal.fire('Error', xhr.responseText || 'Server error updating unit type', 'error');
        },
        complete: function() {
            row.removeClass('updating-row');
            $(element).prop('disabled', false);
        }
    });
} */

  function updateUnitType(element) {
      const newUnitType = $(element).val();
      const tid = $(element).data('tid');
      const oldUnit = $(element).data('old-unit');
      const row = $('#row_' + tid);

      row.addClass('updating-row');
      $(element).prop('disabled', true);

      $.ajax({
          url: 'model/fetchTransactions.table2.php',
          method: 'POST',
          data: {
              action: 'update_unit_type',
              tid: tid,
              new_unit_type: newUnitType
          },
          dataType: 'json',
          success: function(response) {
              if (response.status) {
                  $(element).data('old-unit', newUnitType).attr('data-old-unit', newUnitType);

                  // 1. Update price and amounts
                  $('#price_' + tid).val(parseFloat(response.new_price).toFixed());
                  $('#price_' + tid).data('old-price', response.new_price);
                  row.data('price', response.new_price);

                  $('#amount_' + tid).text(parseFloat(response.new_amount).toLocaleString(undefined, {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                  }));

                  const newTotal = parseFloat(response.new_total).toLocaleString(undefined, {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                  });
                  $('tfoot td:last-child').html('<strong>Total Amount:</strong> ' + newTotal);

                  // 2. Update type code label text (e.g., ctn, hlf, qtr, pcs)
                  if (response.type_code) {
                      $('#type_code_label_' + tid).text(response.type_code);
                  }

                  // 3. Hide total pieces text if unit is Piece ('pc' or '4'), otherwise show it
                  const unitKey = (response.unit_key || '').toLowerCase();
                  if (unitKey === 'pc' || newUnitType == '4') {
                      $('#total_pcs_span_' + tid).hide();
                  } else {
                      $('#total_pcs_span_' + tid).show();
                  }

                  row.css('backgroundColor', '#d4edda');
                  setTimeout(() => row.css('backgroundColor', ''), 1000);
              } else {
                  $(element).val(oldUnit);
                  Swal.fire('Error', response.error || 'Failed to update unit type', 'error');
              }
          },
          error: function(xhr) {
              $(element).val(oldUnit);
              Swal.fire('Error', xhr.responseText || 'Server error updating unit type', 'error');
          },
          complete: function() {
              row.removeClass('updating-row');
              $(element).prop('disabled', false);
          }
      });
  }
</script>