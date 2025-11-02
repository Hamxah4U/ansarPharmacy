<?php
  require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    session_start();
    $errors = [];
    $success = [];
    $cid = htmlspecialchars($_POST['cid']);
    $narration = $_POST['narration'];
    $amount = $_POST['amount'];
    $fname = $_POST['fname'];
    $phone = $_POST['phone'];

    if(empty(trim($narration))){
      $errors['narration'] = 'Required!';
    }

    if(empty(trim($amount))){
      $errors['amount'] = 'Required!';
    }

    if(empty($errors)){
        $stmt = $db->conn->prepare('INSERT INTO `transaction_tbl` (`Amount`, `narration`,`CID`, `TransacDate`, TransacTime, TrasacBy, Customer, tCode, `Status`, creditstatus, cash ) VALUES (:amount, :narr, :cid, CURDATE(), CURRENT_TIME(), :TrasacBy, :Customer,:tCode, :Status, :creditstatus, :cash ) ');
        $result = $stmt->execute([
          ':amount' => $amount,
          ':narr' => $narration,
          ':cid' => $cid,
          ':TrasacBy' => $_SESSION['email'],
          ':Customer' => $fname,
          ':tCode' => $phone, 
          ':Status' => 'Paid',
          ':creditstatus' => 'settlement',
          'cash' => $amount       
        ]);

        if($result){
          $success['message'] = 'Amount funded successfully!';
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
        'success' => $success,
      ]);
    }
  }
?>