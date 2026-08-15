<?php
  if (isset($_POST['product_id'])) {
  require 'Database.php';

  $productID = $_POST['product_id'];

  $stmt = $db->conn->prepare("SELECT pcs_per_unit, Price, half_price, quarter_price, pc_price, pcs_per_unit, Quantity, Pprice, wholesaleprice FROM supply_tbl WHERE SupplyID = :productID LIMIT 1");
  $stmt->execute(['productID' => $productID]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);

  // Fallbacks if half/quarter/piece prices are not explicitly set
  $pcsPerUnit = !empty($product['pcs_per_unit']) ? (int)$product['pcs_per_unit'] : 1;
  $fullPrice  = floatval($product['Price']);
  $halfPrice  = !empty($product['half_price']) ? floatval($product['half_price']) : ($fullPrice / 2);
  $quarterPrice = !empty($product['quarter_price']) ? floatval($product['quarter_price']) : ($fullPrice / 4);
  $pcPrice    = !empty($product['pc_price']) ? floatval($product['pc_price']) : ($fullPrice / $pcsPerUnit);
  // $pcs_per_unit = !empty($product['pcs_per_unit'] ? floatval($product['pcs_per_unit']) : ($pcs_per_unit);
  $pcs_per_unit = $product['pcs_per_unit'];
  $qty = $product['Quantity'];
  $current_qty = $qty/$pcs_per_unit;
  
  echo json_encode([
    'status' => true,
    'full_price' => $fullPrice,
    'half_price' => $halfPrice,
    'quarter_price' => $quarterPrice,
    'pc_price' => $pcPrice,
    'pcs_per_unit' => $pcsPerUnit,
    'stock_pcs' => (int)$product['Quantity'],
    'purchaprice' => $product['Pprice'],
    'wholesaleprice' => $product['wholesaleprice'],
    'quantity' => $current_qty,
    // 'quantity' => $product['Quantity'],
    'price' => $product['Price'],
  ]);
}


  /* if (isset($_POST['product_id'])) {
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
  } */