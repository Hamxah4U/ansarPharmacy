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

      // Fetch Unit Multiplier from DB
      $stmtU = $db->conn->prepare("SELECT unit_label, multiplier FROM unit_types_tbl WHERE unit_key = :ukey LIMIT 1");
      $stmtU->execute([':ukey' => $unitType]);
      $uRow = $stmtU->fetch(PDO::FETCH_ASSOC);

      $multiplier = $uRow ? floatval($uRow['multiplier']) : 1.0;
      $unitLabel  = $uRow ? $uRow['unit_label'] : '';

      // Calculate Pieces Deducted & Selling Price
      if ($unitType === 'pc') {
          $qtyInPcs  = 1 * $issuedqty;
          $unitPrice = !empty($row['pc_price']) ? floatval($row['pc_price']) : (floatval($row['wholesaleprice']) / $pcsPerUnit);
      } else {
          $qtyInPcs  = ($pcsPerUnit * $multiplier) * $issuedqty;
          
          if ($unitType === 'half' && !empty($row['half_price'])) {
              $unitPrice = floatval($row['half_price']);
          } elseif ($unitType === 'quarter' && !empty($row['quarter_price'])) {
              $unitPrice = floatval($row['quarter_price']);
          } else {
              $unitPrice = floatval($row['wholesaleprice']) * $multiplier;
          }
      }

      // Validate Available Stock in Pieces
      if ($qtyInPcs > $row['Quantity']) {
          $errors['outofStock'] = 'Requested quantity exceeds stock! Available pieces: ' . $row['Quantity'];
      }
    }

    if(empty($errors)){
      $amount = $unitPrice * $issuedqty;

      $stmt = $db->conn->prepare("INSERT INTO transaction_tbl (tCode, tDepartment, Product, Price, qty, unit_type, Amount, Customer, TrasacBy, nhisno, TransacTime, TransacDate, pprice, pcs_per_unit)
       VALUES(:tcode, :tdpt, :product, :price, :qty, :unit_type, :amount, :customer, :TrasacBy, :nhisno, CURRENT_TIME(), CURDATE(), :pprice, :pcs_per_unit ) "); 
       
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
        ':pcs_per_unit' => $row['pcs_per_unit'],
      ]);

      echo json_encode(['status' => true]);
    } else {
      echo json_encode(['status' => false, 'errors' => $errors]); 
    }
  }
?>

<?php
  /* require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    session_start();
    $errors = [];
    $success = [];
    $customername = htmlspecialchars($_POST['customername']);
    $dpt = htmlentities($_POST['dpt']);
    $product = isset($_POST['product']) ? htmlspecialchars($_POST['product']) : '';
    $tcode = htmlspecialchars($_POST['tcode']);
    $price = htmlspecialchars($_POST['cprice']);
    $qty = htmlspecialchars($_POST['qty']);
    $nhisno = htmlspecialchars($_POST['nhisno']);
    $user = $_SESSION['email'];
    $issuedqty = htmlentities($_POST['issuedqty']);
    $purchaseprice = htmlspecialchars($_POST['purchaseprice']);

    $TestProduct = 29;

    $stmtExist = $db->checkExist('SELECT `tCode`, `tDepartment`, `Product` FROM `transaction_tbl` WHERE `tCode` = :tCode AND `tDepartment` = :tdpt AND `Product` = :Product',[':tCode' => $tcode, ':tdpt' => $dpt, ':Product' => $product ]);
    $proExist = $stmtExist->rowCount();

    $stmtqty = $db->checkExist('SELECT * FROM `supply_tbl` WHERE `Department` = :dpt AND `SupplyID` = :ProductName', [
      ':dpt' => $dpt,
      ':ProductName' => $product
    ]);
      $row = $stmtqty->fetch(PDO::FETCH_ASSOC);

    if($issuedqty > $qty){
      $errors['outofStock'] = 'The requested quantity exceeds the available stock!';
    }

    if($proExist > 0){
      $errors['proExist'] = 'Product already on the list!';
    }

    if(!empty($issuedqty) && intval($issuedqty) <= 0){
      $errors['issuedqty_'] = 'Issued quantity cannot be less than or equal to 0';
    }

    if(empty(trim($issuedqty))){
      $errors['issuedqty'] = 'Issued quantity is required';
    }

    if ($product == '--choose--' || empty($product)) {
      $errors['product'] = 'Product is required!';
    }

    if($dpt == '--choose--'){
      $errors['unit'] = 'Department is required!';
    }

    if(empty(trim($customername))){
      $errors['customer'] = 'Customer name is required!';
    }

    if(empty($errors)){
      $amount =  $price * $issuedqty;
      $purchasepricetotal = $purchaseprice * $issuedqty;
      $stmt = $db->conn->prepare("INSERT INTO transaction_tbl (tCode, tDepartment, Product, Price, qty, Amount, Customer, TrasacBy, nhisno, TransacTime, TransacDate,pprice)
       VALUES(:tcode, :tdpt, :product, :price, :qty, :amount, :customer, :TrasacBy, :nhisno, CURRENT_TIME(), CURDATE(), :pprice  ) ");
      $stmt->bindParam(':tcode', $tcode, PDO::PARAM_STR);
      $stmt->bindParam(':tdpt', $dpt, PDO::PARAM_STR);
      $stmt->bindParam(':product', $product, PDO::PARAM_STR);
      $stmt->bindParam(':price', $price, PDO::PARAM_INT);
      $stmt->bindParam(':qty', $issuedqty);
      $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
      $stmt->bindParam(':customer', $customername, PDO::PARAM_STR);
      $stmt->bindParam(':TrasacBy', $user);
      $stmt->bindParam(':nhisno', $nhisno, PDO::PARAM_INT);
      $stmt->bindParam(':pprice',  $purchaseprice);
      $result = $stmt->execute();
    }

    if(count($errors) > 0){
      echo json_encode([
        'status' => false,
        'errors' => $errors,
      ]);
    }else{
      echo json_encode([
        'status' => true,
        'success' => $success,
      ]);
    }
  } */
?>