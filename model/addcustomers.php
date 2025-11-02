<?php
require 'Database.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fname = $_POST['fname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $errors = [];
    $success = [];
    $pass = 'password';
    $password = password_hash($pass, PASSWORD_BCRYPT);

    $stmtPhone = $db->checkExist('SELECT `Phone` FROM `customers_tbl` WHERE `Phone` = :phone', ['phone' => $phone]);
    $phoneExist = $stmtPhone->rowCount();

    $stmtEmail = $db->checkExist('SELECT `email` FROM `customers_tbl` WHERE `email` = :email', ['email' => $email]);
    $emailExist = $stmtEmail->rowCount();

    if($emailExist > 0 && $email != null){
      $errors['email'] = 'Customer with this email already exist!';
    }

    if(empty(trim($fname))){
      $errors['fullname'] = 'Required!';
    }

    if(empty(trim($phone))){
      $errors['phone'] = 'Required!';
    }elseif($phoneExist > 0){
      $errors['phone'] = 'Customer already exist!';
    }

    if(empty(trim($address))){
      $errors['address'] = 'Required!';
    }

    if(empty($errors)){
      session_start();
      $user = $_SESSION['email'];
      $stmt = $db->conn->prepare('INSERT INTO `customers_tbl` (`Fullname`, `phone`, `email`, `password`, `address`, `created_by`) VALUES (:Fullname, :phone, :email, :password, :address, :created_by ) ');
      $result = $stmt->execute([
        ':Fullname' => $fname,
        ':phone' => $phone,
        ':email' => $email,
        ':password' => $password,
        ':address' => $address,
        ':created_by' => $user,
      ]);

      if($result){
        $success['message'] = 'Customer added successfully';
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
