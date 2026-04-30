<?php
require 'Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tcode = htmlspecialchars($_POST['tcode']);
    $customername = htmlspecialchars($_POST['customername']);
    $department = htmlspecialchars($_POST['department']);
    
    try {
        $db->conn->beginTransaction();
        
        // Update customer name for all items with this tCode
        $stmtCustomer = $db->conn->prepare("UPDATE transaction_tbl SET Customer = :customer WHERE tCode = :tcode");
        $stmtCustomer->execute([':customer' => $customername, ':tcode' => $tcode]);
        
        // Update department for all items with this tCode
        $stmtDept = $db->conn->prepare("UPDATE transaction_tbl SET tDepartment = :dept WHERE tCode = :tcode");
        $stmtDept->execute([':dept' => $department, ':tcode' => $tcode]);
        
        $db->conn->commit();
        
        echo json_encode([
            'status' => true,
            'message' => 'Transaction header updated successfully'
        ]);
    } catch(Exception $e) {
        $db->conn->rollBack();
        echo json_encode([
            'status' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => false,
        'error' => 'Invalid request'
    ]);
}
?>