<?php
require 'Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tcode'])) {
    $tCode = htmlspecialchars($_POST['tcode']);
    
    try {
        // Get the total amount from transaction_tbl
        $stmt = $db->conn->prepare('SELECT SUM(Amount) AS total FROM transaction_tbl WHERE tCode = :tCode AND Status = "Not-Paid"');
        $stmt->execute([':tCode' => $tCode]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $total = $result['total'] ?? 0;
        
        // Ensure total is a number
        $total = floatval($total);
        
        echo json_encode([
            'status' => true,
            'total' => $total
        ]);
    } catch(Exception $e) {
        echo json_encode([
            'status' => false,
            'total' => 0,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => false,
        'total' => 0,
        'error' => 'Invalid request'
    ]);
}
?>