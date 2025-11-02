<?php
if (isset($_POST['product_id'])) {
  require 'Database.php';

  $productID = $_POST['product_id'];

  $stmt = $db->conn->prepare("SELECT wholesaleprice, Price, Quantity, Pprice FROM supply_tbl WHERE SupplyID = :productID ORDER BY SupplyDate DESC ");
  $stmt->execute(['productID' => $productID]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);

  echo json_encode([
    'price' => $product['Price'],
    'quantity' => $product['Quantity'],
    'purchaprice' => $product['Pprice'],
    'wholesaleprice' => $product['wholesaleprice']
  ]);
}