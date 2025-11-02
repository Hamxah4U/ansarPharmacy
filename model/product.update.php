<?php
      require 'Database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $errors = [];
      $success = [];
      $id = $_POST['productID'];
      $unit = htmlspecialchars($_POST['unit']);
      $product= htmlspecialchars($_POST['product']);
      $price = htmlspecialchars($_POST['price']);

      if($unit == '--choose--'){
        $errors['unit'] = 'Department cannot be empty!';
      }

      if(empty(trim($product))){
        $errors['product'] = 'Product cannot be empty!';
      }

      if(empty(trim($price))){
        $errors['price'] = 'Price cannot be empty!';
      }

      if($unit == '--choose--'){
        $errors['unit'] = 'Department cannot be empty!';
      }

      if(empty($errors)){
        $stmt = $db->conn->prepare('UPDATE `product_tbl` SET `Productname` = :product, `Price` = :price, `Department` = :unit WHERE `product_tbl`.`proID` = :id ');
        $stmt->bindParam(':product', $product, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':unit', $unit, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        if($result){
          $success['message'] = 'Record updated successfull!';
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
          'success' => $success['message'],
        ]);
      }

    }
?>