<?php
    require 'Database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $newPassword = $_POST['newPassword'];
      $confirmPassword = htmlspecialchars($_POST['confirmPassword']);
      $errors = [];
      $success = [];

      if(empty(trim($newPassword))){
        $errors['newPassword'] = 'This field is required!';
      }

      if(empty(trim($confirmPassword))){
        $errors['confirmPassword'] = 'This field is required!';
      }

      if($newPassword !== $confirmPassword){
        $errors['passmismatch'] = 'Password do not match!';
      }

      if(empty($errors)){
        session_start();
        $userID = $_SESSION['userID'];
        $stmt = $db->checkExist('UPDATE `users_tbl` SET `UserPassword` = :UserPassword WHERE `userID` = :userID',[
          ':UserPassword' => password_hash($newPassword, PASSWORD_BCRYPT),
          ':userID' => $userID
        ]);
        $success['message'] = 'Password change successfully!';
        $success['redirect'] = '/';
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