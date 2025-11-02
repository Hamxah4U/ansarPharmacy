<?php
if(isset($_POST['search'])) {
  $search = $_POST['search'];
  $stmt = $db->prepare("SELECT * FROM `products_tbl` WHERE `product_name` LIKE ?");
  $stmt->execute(["%$search%"]);
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $results = [];
  foreach($products as $product) {
    $results[] = [
      'label' => $product['product_name'],
      'value' => $product['productID']
    ];
  }
  echo json_encode($results);
}
?>
