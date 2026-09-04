<?php
  require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    session_start();
    $errors = [];
    
    $customername = htmlspecialchars($_POST['customername']); 
    $dpt          = htmlentities($_POST['dpt']); 
    $product      = isset($_POST['product']) ? htmlspecialchars($_POST['product']) : ''; 
    $tcode        = htmlspecialchars($_POST['tcode']); 
    $unitType     = $_POST['unit_type'] ?? 'full'; 
    $issuedqty    = floatval($_POST['issuedqty']); 
    $user         = $_SESSION['email']; 
    $nhisno       = htmlspecialchars($_POST['nhisno'] ?? ''); 
    $unit_type    = htmlspecialchars($_POST['unit_type'] ?? null);

    // 1. Check if product with the exact same store (department), product ID, and unit_type already exists on this receipt
    $stmtExist = $db->conn->prepare("
        SELECT COUNT(*) FROM `transaction_tbl` 
        WHERE `tCode` = :tcode 
          AND `tDepartment` = :dpt 
          AND `Product` = :product 
          AND (`unit_type` = :unit_type OR (`unit_type` IS NULL AND :unit_type_check IS NULL))
          AND `Status` != 'Returned'
    ");
    $stmtExist->execute([
        ':tcode'            => $tcode,
        ':dpt'              => $dpt,
        ':product'          => $product,
        ':unit_type'        => $unit_type,
        ':unit_type_check'  => $unit_type
    ]);

    if ($stmtExist->fetchColumn() > 0) {
        $errors['proExist'] = 'Product with this unit type already exists in the transaction list!';
    }

    // 2. Fetch Product details from supply table
    $stmtqty = $db->checkExist('SELECT * FROM `supply_tbl` WHERE `Department` = :dpt AND `SupplyID` = :ProductName', [ 
      ':dpt' => $dpt, 
      ':ProductName' => $product 
    ]);
    $row = $stmtqty->fetch(PDO::FETCH_ASSOC); 

    if (!$row) {
      $errors['product'] = 'Product not found!';
    } else {
      $pcsPerUnit = !empty($row['pcs_per_unit']) ? intval($row['pcs_per_unit']) : 1;

      // Fetch Unit details using EITHER numeric ID or unit_key string safely
      $stmtU = $db->conn->prepare("SELECT id, unit_key, unit_label, multiplier FROM unit_types_tbl WHERE id = :ukey OR unit_key = :ukey LIMIT 1");
      $stmtU->execute([':ukey' => $unitType]);
      $uRow = $stmtU->fetch(PDO::FETCH_ASSOC);

      $unitKey    = $uRow ? $uRow['unit_key'] : $unitType;
      $multiplier = $uRow ? floatval($uRow['multiplier']) : 1.0;

      // Calculate Deducted Stock Quantity & Unit Selling Price dynamically
      if ($unitKey === 'pc') {
          $qtyInPcs  = 1 * $issuedqty;
          $unitPrice = !empty($row['pc_price']) ? floatval($row['pc_price']) : (floatval($row['Price']) / $pcsPerUnit);
      } else {
          $qtyInPcs  = ($pcsPerUnit * $multiplier) * $issuedqty;
          
          if ($unitKey === 'half' && !empty($row['half_price'])) {
              $unitPrice = floatval($row['half_price']);
          } elseif ($unitKey === 'quarter' && !empty($row['quarter_price'])) {
              $unitPrice = floatval($row['quarter_price']);
          } else {
              $unitPrice = floatval($row['Price']) * $multiplier;
          }
      }

      // Validate Available Stock in Pieces
      if ($qtyInPcs > $row['Quantity']) {
          $errors['outofStock'] = 'Requested quantity exceeds stock! Available pieces: ' . $row['Quantity'];
      }
    }

    if(empty($errors)){
      $amount = $unitPrice * $issuedqty;

      $stmt = $db->conn->prepare("INSERT INTO transaction_tbl (tCode, tDepartment, Product, Price, qty, unit_type, Amount, Customer, TrasacBy, nhisno, TransacTime, TransacDate, pprice, pcs_per_unit_v)
       VALUES(:tcode, :tdpt, :product, :price, :qty, :unit_type, :amount, :customer, :TrasacBy, :nhisno, CURRENT_TIME(), CURDATE(), :pprice, :pcs_per_unit_v ) "); 
       
      $stmt->execute([
        ':tcode'     => $tcode, 
        ':tdpt'      => $dpt, 
        ':product'   => $product, 
        ':price'     => $unitPrice,
        ':qty'       => $qtyInPcs, 
        ':amount'    => $amount,
        ':customer'  => $customername, 
        ':TrasacBy'  => $user, 
        ':nhisno'    => $nhisno, 
        ':pprice'    => $row['Pprice'],
        ':unit_type' => $unit_type,
        'pcs_per_unit_v' => $row['pcs_per_unit']
      ]);

      echo json_encode(['status' => true]);
    } else {
      echo json_encode(['status' => false, 'errors' => $errors]); 
    }
  }
?>