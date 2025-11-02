<?php
  require 'Database.php';
  $id = $_GET['deptId'];

  $stmt = $db->conn->prepare('SELECT * FROM `department_tbl` WHERE deptID = :id');
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $dept = $stmt->fetch(PDO::FETCH_ASSOC);
  echo json_encode($dept);