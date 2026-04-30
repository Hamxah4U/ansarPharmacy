<?php
require 'Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tid'])) {
    $tid = htmlspecialchars($_POST['tid']);
    
    try {
        $stmt = $db->conn->prepare("DELETE FROM transaction_tbl WHERE TID = :tid AND Status = 'Not-Paid'");
        $stmt->execute([':tid' => $tid]);
        
        if($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => true,
                'message' => 'Transaction deleted successfully'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'errors' => ['error' => 'Transaction not found or already paid']
            ]);
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => false,
            'errors' => ['error' => $e->getMessage()]
        ]);
    }
} else {
    echo json_encode([
        'status' => false,
        'errors' => ['error' => 'Invalid request']
    ]);
}
?>