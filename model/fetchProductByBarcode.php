<?php
require 'Database.php';

header('Content-Type: application/json');

if(isset($_POST['barcode'])) {

    $barcode = $_POST['barcode'];

    $stmt = $db->conn->prepare("
        SELECT 
            s.SupplyID,
            s.ProductName,
            s.productcode,
            s.Department,
            s.Price,
            s.StockQuantity,
            d.Department AS deptName
        FROM supply_tbl s
        JOIN department_tbl d ON s.Department = d.deptID
        WHERE s.productcode = ?
        AND s.Status = 'Active'
        LIMIT 1
    ");

    $stmt->execute([$barcode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product) {
        echo json_encode([
            'status' => true,
            'data' => $product
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Product not found'
        ]);
    }
}