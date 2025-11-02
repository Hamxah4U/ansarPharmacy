<?php
  require 'Database.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $errors = [];
      $success = [];

      $unit = $_POST['unit'];
      $product = $_POST['product'];
      $price = $_POST['price'];

      $stmtExist = $db->checkExist('SELECT `Department`, `Productname` FROM `product_tbl` WHERE `Department` = :unit AND `Productname` = :product', ['unit' => $unit, 'product' => $product]);
      $productExist = $stmtExist->rowCount();

      if($productExist > 0){
        $errors['productExist'] = 'Product and Department already exist!';
      }

      if(empty(trim($product))){
        $errors['product'] = 'Product or service are required!';
      }

      if(empty(trim($price))){
        $errors['price'] = 'Price is required!';
      }elseif(! empty(floatval($price))){
        $errors['price'] = 'Price cannot be less than 0';
      }

      if($unit == '--choose--'){
        $errors['unit'] = 'Unit or Department is required!';
      }

      if(empty($errors)){
        session_start();
        $stmt = $db->conn->prepare('INSERT INTO `product_tbl` (`Department`,`Productname`,`Price`,`RegisterBy`) VALUES (:unit, :product, :price, :user) ');
        $user = $_SESSION['email'];
        $stmt->bindParam(':unit', $unit, PDO::PARAM_STR);
        $stmt->bindParam(':product', $product, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $result = $stmt->execute();
        if($result){
          $success['message'] = 'Service/Product and Price save successull!';
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
          'message' => $success['message'] ?? '',
        ]);
      }
    }
?>