<?php
    require 'Database.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $amount = htmlspecialchars($_POST['amount']);
    $collectby = htmlspecialchars($_POST['collectby']);
    $reason = htmlspecialchars($_POST['reason']);

    $errors = [];
    $success = [];

    if(empty(trim($amount))){
      $errors['amount'] = 'Amount is required!';
    }

    if(empty(trim($collectby))){
      $errors['collectby'] = 'Collector name is required!';
    }

    if(empty(trim($reason))){
      $errors['reason'] = 'Reason is required!';
    }

    if(empty($errors)){
      $db = new Database();
      session_start();
      $user = $_SESSION['email'];
      $stmt = $db->conn->prepare(
        'INSERT INTO `financecollect_tbl` (Collectorname, Amount, Reason, Givername) 
        VALUES (:c, :a, :r, :g)'
    );

    $stmt->bindParam(':c', $collectby, PDO::PARAM_STR);
    $stmt->bindParam(':a', $amount, PDO::PARAM_INT);
    $stmt->bindParam(':r', $reason, PDO::PARAM_STR);
    $stmt->bindParam(':g', $user, PDO::PARAM_STR);

    $result = $stmt->execute();

      if($result){
        $success['success'] = 'Save successfully!';
      }
      
    }
    
    if(count($errors) > 0){
      echo json_encode([
        'status' => false,
        'errors' => $errors,
      ]);
    }else{
      echo json_encode([
        'status' => true,
        'success' => $success
      ]);
    }

  }
?>