<?php
  require 'Database.php';
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['unitId'];
    $department = $_POST['unit'];
   
    $errors = [];
    $success = [];

    if(empty(trim($department))){
      $errors['unitUpdate'] = 'Unit/Department cannot be empty!';
    }

    if(empty($errors)){
      $stmt = $db->conn->prepare('UPDATE `department_tbl` SET `Department` = :unit WHERE `department_tbl`.`deptID` = :DID');
      $stmt->bindParam(':unit', $department, PDO::PARAM_STR);
      $stmt->bindParam(':DID', $id, PDO::PARAM_INT);
      $result = $stmt->execute();
      if($result){
        $success['message'] = 'Department updated successfully!';
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
        'success' => $success['message']
      ]);
    }
  }
?>