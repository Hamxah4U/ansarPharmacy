<?php
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tCode'])) {
    $tCode = htmlspecialchars($_POST['tCode']);
    $cash = floatval($_POST['cash'] ?? 0);
    $transfer = floatval($_POST['transfer'] ?? 0);
    $pos = floatval($_POST['pos'] ?? 0);
    $errors = [];
    
    try {
        $db->conn->beginTransaction();
        
        // Get the total transaction amount first
        $stmtTotalAmount = $db->conn->prepare('SELECT SUM(Amount) AS total_ex FROM transaction_tbl WHERE tCode = :tCode AND Status = "Not-Paid"');
        $stmtTotalAmount->execute([':tCode' => $tCode]);
        $totalAmount = $stmtTotalAmount->fetch(PDO::FETCH_ASSOC);
        
        $expectedTotal = floatval($totalAmount['total_ex'] ?? 0);
        $paidTotal = $cash + $transfer + $pos;
        
        // Validate payment matches total
        if ($paidTotal != $expectedTotal) {
            throw new Exception("Total payment (₦" . number_format($paidTotal, 2) . ") does not match transaction total (₦" . number_format($expectedTotal, 2) . ").");
        }
        
        // Step 1: Update transaction status and payment details for ALL items with this tCode
        $stmt = $db->conn->prepare('UPDATE transaction_tbl 
            SET `pos` = :pos, `transfer` = :transfer, `cash` = :cash, `Status` = "Paid" 
            WHERE tCode = :tCode AND Status = "Not-Paid"
        ');
        
        $stmt->execute([
            ':tCode' => $tCode,
            ':cash' => $cash,
            ':transfer' => $transfer,
            ':pos' => $pos
        ]);
        
        // Check how many rows were updated
        $rowsUpdated = $stmt->rowCount();
        error_log("Updated $rowsUpdated rows for tCode: $tCode");
        
        if($rowsUpdated == 0) {
            throw new Exception('No pending transactions found to update for code: ' . $tCode);
        }
        
        // Step 2: Update stock quantities for all items
        $stmtFetchTransactions = $db->conn->prepare('
            SELECT Product, tDepartment, qty 
            FROM transaction_tbl 
            WHERE tCode = :tCode AND Status = "Paid"
        ');
        $stmtFetchTransactions->execute([':tCode' => $tCode]);
        $transactions = $stmtFetchTransactions->fetchAll(PDO::FETCH_ASSOC);
        
        if (!$transactions) {
            throw new Exception('No transaction records found after update.');
        }
        
        foreach ($transactions as $transaction) {
            $stmtSupply = $db->conn->prepare('
                SELECT Quantity 
                FROM supply_tbl 
                WHERE Department = :department AND SupplyID = :product
            ');
            $stmtSupply->execute([
                ':department' => $transaction['tDepartment'],
                ':product' => $transaction['Product']
            ]);
            $supply = $stmtSupply->fetch(PDO::FETCH_ASSOC);
            
            if (!$supply) {
                throw new Exception('Supply record not found for product: ' . $transaction['Product']);
            }
            
            $newQuantity = floatval($supply['Quantity']) - floatval($transaction['qty']);
            if ($newQuantity < 0) {
                throw new Exception('Insufficient stock for product: ' . $transaction['Product']);
            }
            
            $stmtUpdateSupply = $db->conn->prepare('
                UPDATE supply_tbl 
                SET Quantity = :newQuantity 
                WHERE SupplyID = :product AND Department = :department
            ');
            $stmtUpdateSupply->execute([
                ':newQuantity' => $newQuantity,
                ':product' => $transaction['Product'],
                ':department' => $transaction['tDepartment']
            ]);
        }
        
        // Commit the transaction
        $db->conn->commit();
        
        echo json_encode([
            'status' => true,
            'message' => 'Transaction validated and stock updated successfully.',
            'rows_updated' => $rowsUpdated
        ]);
    } catch (Exception $e) {
        $db->conn->rollBack();
        error_log("Validation error: " . $e->getMessage());
        echo json_encode([
            'status' => false,
            'errors' => ['error' => $e->getMessage()]
        ]);
    }
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid request.'
    ]);
}  
?>