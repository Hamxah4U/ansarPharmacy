<?php
  require 'Database.php';
  $stmt = $db->query('SELECT * FROM `department_tbl`');
  $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($units);
									