<?php
  require 'Database.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = [];
    $success = [];
    $unit = trim($_POST['unit']);

    $stmtUnit = $db->checkExist('SELECT `Department` FROM `department_tbl` WHERE `Department` = :department', ['department' => $unit]);
    $unitExist = $stmtUnit->rowCount();

    if($unitExist > 0){
      $errors['unitExist'] = 'Unit/Department already exist!';
    }

    if(empty($unit)){
      $errors['unit'] = 'Unit or Department are required!';
    }

    if(empty($errors)){
      session_start();
      $user = $_SESSION['email'];
      $stmt = $db->conn->prepare('INSERT INTO `department_tbl` (`Department`, `registerby`) VALUES (:department, :registerby)');
      $stmt->bindParam(':department', $unit, PDO::PARAM_STR);
      $stmt->bindParam(':registerby', $user, PDO::PARAM_STR);

      $result = $stmt->execute();

      if($result){
        $success['message'] = 'Department save successfull!';
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
        'message' => $success['message'] ?? '',
      ]);
    }

  }
?>