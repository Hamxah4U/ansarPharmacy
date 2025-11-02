<?php
  require 'Database.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errors = [];
    $success = [];

    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $id = $_POST['stdID'];

    if(empty(trim($fname))){
      $errors['errorFirstname'] = 'Fullname cannot be empty!';
    }

    if(empty(trim($email))){
      $errors['email'] = 'Email cannot be empty';
    }

    if(empty(trim($phone))){
      $errors['phone'] = 'Phone cannot be empty';
    }

    if (empty($errors)) {
      session_start();
      try {
          $stmt = $db->conn->prepare("UPDATE `users_tbl` SET `Phone` = :Phone, `Fullname` = :fname WHERE `users_tbl`.`userID` = :userID");
  
          $result = $stmt->execute([
              ':fname' => $fname,
              ':Phone' => $phone,
              ':userID' => $_SESSION['userID']
          ]);
  
          if ($result) {
              $success['message'] = 'Profile updated successfully!';
          } else {
              $errors[] = 'Error updating profile. Please try again.';
          }
      } catch (PDOException $e) {
          $errors[] = 'Database error: ' . $e->getMessage();
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
        'success' => $success
      ]);
    }

  }
?>