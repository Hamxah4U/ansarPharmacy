<?php
// require 'partials/security.php';
require 'Database.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplyId      = filter_input(INPUT_POST, 'supply_id', FILTER_VALIDATE_INT);
    $productName   = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $virtualStock  = filter_input(INPUT_POST, 'virtual_stock', FILTER_VALIDATE_INT);
    $physicalStock = filter_input(INPUT_POST, 'physical_stock', FILTER_VALIDATE_INT);
    $discrepancy   = filter_input(INPUT_POST, 'discrepancy', FILTER_VALIDATE_INT);
    $status        = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $auditedBy     = $_SESSION['userID'] ?? ($_SESSION['username'] ?? 'System User');

    if ($supplyId === false || $virtualStock === false || $physicalStock === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid data payload.']);
        exit;
    }

    try {

        $query = "INSERT INTO stock_reconciliation_logs 
                    (SupplyID, ProductName, VirtualStock, PhysicalStock, Discrepancy, Status, AuditedBy) 
                  VALUES 
                    (:supply_id, :product_name, :virtual_stock, :physical_stock, :discrepancy, :status, :audited_by)";

        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':supply_id'      => $supplyId,
            ':product_name'   => $productName,
            ':virtual_stock'  => $virtualStock,
            ':physical_stock' => $physicalStock,
            ':discrepancy'    => $discrepancy,
            ':status'         => $status,
            ':audited_by'     => $auditedBy
        ]);

        echo json_encode(['success' => true, 'message' => 'Audit logged successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}