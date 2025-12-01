<?php
require 'Database.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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



  $stmtEmail = $db->checkExist('SELECT `Email` FROM `users_tbl` WHERE `Email` = :email', ['email' => $email]);
  $emailExist = $stmtEmail->rowCount();

  $stmtPhone = $db->checkExist('SELECT `Phone` FROM `users_tbl` WHERE `Phone` = :phone', ['phone' => $phone]);
  $phoneExist = $stmtPhone->rowCount();


  if($phoneExist > 0){
    $errors['phoneExist'] = 'Phone number already exists!';
  }

  if($emailExist > 0){
    $errors['emailExist'] = 'Email address already exists!';
  }

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
    try{
      $db = new Database();
      $stmt = $db->conn->prepare('INSERT INTO `users_tbl` (`Fullname`,`Email`,`Phone`,`UserPassword`,`Department`,`Role`) VALUES (:fname, :email, :phone, :pass ,:department, :userrole) ');
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
      $stmt->bindParam(':department', $unit, PDO::PARAM_INT);
      $stmt->bindParam(':userrole', $role, PDO::PARAM_STR);
      $result = $stmt->execute();

      if ($result) {
        $success['message'] = 'User successfully added, and email sent.';

        // Send Email
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
            $mail->addAddress($email, $fname); 
            $mail->isHTML(true);
            $mail->Subject = 'Registration Successful';
            $mail->Body = "
              <h2 style='color: #333; font-family: Arial, sans-serif;'>Welcome to Shafa Global Fertilizer Enterprise, Mr./Mrs. $fname!</h2>
              <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                We are thrilled to have you on board! Your registration was successfully completed, and you can now access your account using the following credentials:
              </p>
              <ul style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                <li><strong>Username:</strong> $email or $phone</li>
                <li><strong>Temporary Password:</strong> $pass</li>
              </ul>
              <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                For security purposes, we strongly recommend updating your password immediately after logging in. Click the button below to log in and access your account:
              </p>
              <p style='text-align: center;'>
                <a href='https://sfge.org.ng/' style='text-decoration: none;'>
                  <button style='background-color: #007bff; color: #fff; border: none; padding: 12px 24px; font-size: 16px; border-radius: 5px; cursor: pointer;'>
                    Log In to Your Account
                  </button>
                </a>
              </p>
              <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                If you have any questions or encounter any issues, please do not hesitate to reach out to our support team at <a href='mailto:info.shafafertilizer@sfge.org.ng' style='color: #007bff;'>info.shafafertilizer@sfge.org.ng</a>.
              </p>
              <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                Thank you for choosing Shafa Global Fertilizer Enterprise. We look forward to serving you.
              </p>
              <p style='font-size: 16px; color: #555; font-family: Arial, sans-serif;'>
                Best regards,<br>
                <strong>The Shafa Global Fertilizer Enterprise Team</strong>
              </p>
            ";

            $mail->send();
            $success['message'] = 'User successfully added, and email sent.';
        } catch (Exception $e) {
            $errors['emailError'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        }
    }
      if($result){
        echo json_encode([
        'status' => true,
        'success' => ['message' => 'User successfully added and email sent.']
        ]);
        exit;
      }
    }catch(PDOException $e){
      die('Error:'. $e->getMessage());
    }
  }

  if (count($errors) > 0) {
      echo json_encode([
          'status' => false,
          'errors' => $errors,
      ]);
  } else {
      echo json_encode([
        'status' => true,
        'success' => ['message' => 'User successfully added']
      ]);
  }
}

?>