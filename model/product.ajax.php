<?php

if (isset($_POST['department_id'])) {
  require 'Database.php';

  $deptID = $_POST['department_id'];
  $qty = 0;

  $stmt = $db->conn->prepare("SELECT Quantity, SupplyID, ProductName FROM supply_tbl WHERE Department = :deptID AND Quantity > :qty");
  $stmt->execute(['deptID' => $deptID, ':qty' => $qty]);
  $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo '<option value="--select product--">--choose--</option>';
  foreach ($services as $service) {
    echo '<option value="'.$service['SupplyID'].'">'.$service['ProductName'].'</option>';
  }
}    

?>