<?php
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $success = [];

    $id = $_POST['supplyID']; 
    $unit = htmlspecialchars($_POST['unit']);
    $product = htmlspecialchars($_POST['product']);
    $qty = htmlspecialchars($_POST['qty']);
    $price = htmlspecialchars($_POST['price']);
    $expiryDate = htmlspecialchars($_POST['ExpiryDate']);
    $SupplyID = htmlspecialchars($_POST['supplyID']);
    $purchasePrice = htmlspecialchars($_POST['purchasePrice']);

    // update
    $wholesale = htmlspecialchars($_POST['wholesale']);
    // end od update

    if(empty(trim($purchasePrice))){
      $errors['purchasePrice'] = 'Purchase price cannot be empty!';
    }

    if ($unit == '--choose--') {
      $errors['unit'] = 'Department cannot be empty!';
    }

    if (empty(trim($product))) {
        $errors['product'] = 'Product cannot be empty!';
    }

    if (empty(trim($qty))) {
        $errors['qty'] = 'Quantity cannot be empty!';
    }

    if (empty(trim($price))) {
        $errors['price'] = 'Price cannot be empty!';
    }

    if (empty(trim($expiryDate))) {
        $errors['ExpiryDate'] = 'Expiry Date cannot be empty!';
    }

    if(empty($errors)){
      $stmt = $db->conn->prepare("UPDATE `supply_tbl` SET `Quantity` = :Quantity, `wholesaleprice` = :wholesale, `Pprice` = :Pprice, `ProductName` = :product, `ExpiryDate` = :ExpiryDate,  `Price` = :Price, Department = :Department WHERE `SupplyID` = :id ");
      $stmt->bindParam(':product', $product, PDO::PARAM_STR);
      $stmt->bindParam(':ExpiryDate', $expiryDate);
      $stmt->bindParam(':Quantity', $qty);
      $stmt->bindParam(':Price', $price);
      $stmt->bindParam(':Department', $unit);
      $stmt->bindParam(':id', $SupplyID);
      $stmt->bindParam(':Pprice', $purchasePrice);
      // update
      $stmt->bindParam(':wholesale', $wholesale);
      // end of update
      $result = $stmt->execute();
      if($result){
        $success['message'] = 'Record updated!';
      }
    }

    if(count($errors) > 0){
      echo json_encode([
        'status' => false,
        'errors' => $errors
      ]);
    }else{
      echo json_encode([
        'status' => true,
        'message' => $success
      ]);
    }

}
