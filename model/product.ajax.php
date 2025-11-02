<?php
// This file handles AJAX requests for product search and department-based product listing
// if (isset($_POST['query']) && isset($_POST['department_id'])) {
//   require 'Database.php';

//   $query = $_POST['query'];
//   $deptID = $_POST['department_id'];

//   $stmt = $db->conn->prepare("SELECT SupplyID, ProductName FROM supply_tbl WHERE ProductName LIKE :query AND Department = :deptID AND Quantity > 0");
//   $stmt->execute(['query' => '%'.$query.'%', 'deptID' => $deptID]);
//   $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

//   $output = '<ul class="list-group">';
//   if ($stmt->rowCount() > 0) {
//     foreach ($products as $product) {
//       $output .= '<li class="list-group-item product-item" data-id="'.$product['SupplyID'].'">'.$product['ProductName'].'</li>';
//     }
//   } else {
//     $output .= '<li class="list-group-item">No products found</li>';
//   }
//   $output .= '</ul>';

//   echo $output;
// }

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