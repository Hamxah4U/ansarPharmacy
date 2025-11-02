<?php
 
  if (isset($_GET['deptID'])) {

    $deptID = $_GET['deptID'];
    $stmt = $db->conn->prepare('SELECT `proID`, `Productname` FROM `product_tbl` WHERE `Department` = :deparment ');
    $stmt->bindParam(':deparment', $deptID);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
    }else{
    echo json_encode(['error' => 'Department ID not provided']);
  }
?>