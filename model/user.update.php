<?php
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $success = [];
  $errors = [];
  $fname = trim($_POST['fname']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $role = trim($_POST['role']);
  $unit = trim($_POST['unit']);
  $pass = 'password';
  $password = password_hash($pass, PASSWORD_BCRYPT);
  $userID = $_POST['userID'];

    if($unit == '--choose--'){
      $errors['unit'] = 'Please choose a department!';
    }
  
    if(empty($fname)) {
      $errors['fname'] = 'Fullname is required!';
    }
  
    if(empty($email)) {
      $errors['email'] = 'Email is required!';
    }
  
    if(empty($phone)) {
      $errors['phone'] = 'Phone is required!';
    }
  
    if($role == '--choose--') {
      $errors['role'] = 'Please choose a role!';
    }

    if(empty($errors)){
      $stmt = $db->conn->prepare("UPDATE `users_tbl` SET `Fullname` = :Fullname, `Email` = :Email, `Phone` = :Phone, `Role` = :userrole WHERE `userID` = :id ");
      $stmt->bindParam(':Fullname', $fname);
      $stmt->bindParam(':Email', $email);
      $stmt->bindParam(':Phone', $phone);
      $stmt->bindParam(':userrole', $role); 
      $stmt->bindParam(':id', $userID);     
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
