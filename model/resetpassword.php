<?php
require 'Database.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
ob_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db = new Database();
  $conn = $db->conn;
  $errors = [];
  $success = [];

  $email = trim($_POST['email']); 

  if(empty($email)) {
    $errors['email'] = "Email is required!";
  }
  
  if(empty($errors)){
    $status = 'Active';
    $stmt = $conn->prepare('SELECT `Department`, `Role`, `Email`, `Phone`, `UserPassword`, `userID`, `Fullname`  FROM `users_tbl` WHERE `Status` = :userstatus AND `Email` = :email OR `Phone` = :phone');
    $stmt->execute(['email' => $email, 'phone' => $email, 'userstatus' => $status]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $userEmail = $user['Email'];
      $userPhone = $user['Phone'];
      $userID = $user['userID'];  
      $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz`!@#$%^&*()_+-?/.<>\|';
      $newpass = substr(str_shuffle($characters), 8, 10); 
      $hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);
  
      $stmt = $db->query("UPDATE `users_tbl` SET `UserPassword` = '$hashedPassword' WHERE `Email` = '$userEmail' ");      
      if ($stmt) {
          $mail = new PHPMailer(true);
          try {
              // SMTP Server Settings
              $mail->isSMTP();
              $mail->Host = 'sfge.org.ng';
              $mail->SMTPAuth = true;
              $mail->Username = 'info.shafafertilizer@sfge.org.ng';
              $mail->Password = '*H@mxah4u#';
              $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
              $mail->Port = 465;
  
              // Email Settings
              $mail->setFrom('info.shafafertilizer@sfge.org.ng', 'Shafa Global Fertilizer - Admin');
              $mail->addAddress($userEmail, $user['Fullname']);
              $mail->isHTML(true);
              $mail->Subject = 'Password Reset Request';
  
              $mail->Body = "
                  <h2 style='color: #333; font-family: Arial, sans-serif;'>Password Reset Successful</h2>
                  <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                      Hello <strong>{$user['Fullname']}</strong>, <br><br>
                      Your password has been successfully reset. Please use the following credentials to log in to your account:
                  </p>
                  <ul style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                      <li><strong>Username:</strong> $userEmail or $userPhone</li>
                      <li><strong>New Password:</strong> <span style='color: red;'>$newpass</span></li>
                  </ul>
                  <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                      <strong>Important:</strong> For security reasons, please change your password immediately after logging in.
                  </p>
                  <p style='text-align: center;'>
                      <a href='https://sfge.org.ng' style='text-decoration: none;'>
                          <button style='background-color: #007bff; color: #fff; border: none; padding: 12px 24px; font-size: 16px; border-radius: 5px; cursor: pointer;'>
                              Log In to Your Account
                          </button>
                      </a>
                  </p>
                  <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                      If you did not request this password reset, please contact our support team immediately at 
                      <a href='mailto:info.shafafertilizer@sfge.org.ng' style='color: #007bff;'>info.shafafertilizer@sfge.org.ng</a>.
                  </p>
                  <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                      Best regards,<br>
                      <strong>The Shafa Global Fertilizer Enterprise Team</strong>
                  </p>
              ";
  
              $mail->send();
              $success['message'] = 'Password was reset. Check your email.';
              $success['redirect'] = '/';
          } catch (Exception $e) {
              $errors['emailError'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
          }
      }
  } else {
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
