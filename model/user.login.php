<?php
require 'Database.php';
ob_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db = new Database();
  $conn = $db->conn;
  $errors = [];
  $success = [];

  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if(empty($email)) {
    $errors['email'] = "Email or Phone is required!";
  }
  if(empty($password)) {
    $errors['password'] = "Password is required!";
  }

  if(empty($errors)){
    $status = 'Active';
    $stmt = $conn->prepare('SELECT `Department`, `Role`, `Email`, `Phone`, `UserPassword`, `userID`, `Fullname`  FROM `users_tbl` WHERE `Status` = :userstatus AND `Email` = :email OR `Phone` = :phone');
    $stmt->execute(['email' => $email, 'phone' => $email, 'userstatus' => $status]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
      $userEmail = $user['Email'];
      $userPhone = $user['Phone'];
      $userPassword = $user['UserPassword'];
      $userID = $user['userID'];
      $fname = $user['Fullname'];
      $role = $user['Role'];
      $department = $user['Department'];
      
      if(password_verify($password, $userPassword)){
        if($password === 'password'){
          session_start();
          $_SESSION['email'] = $userEmail;
          $_SESSION['phone'] = $userPhone;
          $_SESSION['userID'] = $userID;
          $_SESSION['fname'] = $fname;
          $_SESSION['role'] = $role;
          $_SESSION['department'] = $department;
          $success['message'] = 'Login successful, please wait...';
          $success['redirect'] = '/changepassword';
        }else{
          session_start();
          $_SESSION['email'] = $userEmail;
          $_SESSION['phone'] = $userPhone;
          $_SESSION['userID'] = $userID;
          $_SESSION['fname'] = $fname;
          $_SESSION['role'] = $role;
          $_SESSION['department'] = $department;
          $success['message'] = 'Login successful, please wait...';
          $success['redirect'] = '/dashboard';
        }
        
      }else{
        $errors['invalidpass'] = 'Invalid Password!';
      }
    }else{
      $errors['emailPhone'] = 'Email or Phone does not exist!';
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
