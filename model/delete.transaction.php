<?php
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tid'])) {
    $tid = htmlspecialchars($_POST['tid']);
    $errors = [];
    $success = [];

    $stmt = $db->conn->prepare('DELETE FROM transaction_tbl WHERE `transaction_tbl`.`TID` = :tid');
    $stmt->bindParam(':tid', $tid);
    $result = $stmt->execute();
    if($result){
      $success['message'] = 'Record deleted';
    }else{
      $errors['error'] = 'failed';
    }

    if (empty($errors)) {
      echo json_encode([
          'status' => true, 
          'success' => $success
      ]);
  } else {
      echo json_encode([
          'status' => false, 
          'errors' => $errors 
      ]);
  }
}
?>
