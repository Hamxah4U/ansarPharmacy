<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require 'Database.php';

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
    $cid = htmlspecialchars($_POST['cid']);
    $purchaseprice = htmlspecialchars($_POST['purchaseprice']);
    $email = $_POST['email'];

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
      $stmt = $db->conn->prepare("INSERT INTO transaction_tbl (tCode, tDepartment, Product, Price, qty, Credit, Customer, TrasacBy, nhisno, cid, TransacTime, TransacDate, pprice)
       VALUES(:tcode, :tdpt, :product, :price, :qty, :credit, :customer, :TrasacBy, :nhisno, :cid, CURRENT_TIME(), CURDATE(), :pprice  ) ");
      $stmt->bindParam(':tcode', $tcode, PDO::PARAM_STR);
      $stmt->bindParam(':tdpt', $dpt, PDO::PARAM_STR);
      $stmt->bindParam(':product', $product, PDO::PARAM_STR);
      $stmt->bindParam(':price', $price, PDO::PARAM_INT);
      $stmt->bindParam(':qty', $issuedqty, PDO::PARAM_INT);
      $stmt->bindParam(':credit', $amount, PDO::PARAM_INT);
      $stmt->bindParam(':customer', $customername, PDO::PARAM_STR);
      $stmt->bindParam(':TrasacBy', $user);
      $stmt->bindParam(':nhisno', $nhisno, PDO::PARAM_INT);
      $stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
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
  }
?>