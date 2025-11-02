<?php
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tCode'])) {
    $tCode = htmlspecialchars($_POST['tCode']);
    $cash = $_POST['cash'] ?? 0;
    $transfer = $_POST['transfer'] ?? 0;
    $pos = $_POST['pos'] ?? 0;
    $errors = [];
    try {
        $db->conn->beginTransaction();
        // Step 1: Update transaction status and payment details
        $stmt = $db->conn->prepare('UPDATE transaction_tbl 
            SET `pos` = :pos, `transfer` = :transfer, `cash` = :cash, `Status` = "Paid" 
            WHERE tCode = :tCode
        ');
        
        $stmt->execute([
            ':tCode' => $tCode,
            ':cash' => $cash,
            ':transfer' => $transfer,
            ':pos' => $pos
        ]);
        // Step 2: Calculate total payment methods and total transaction amount
        $stmtTotalMethod = $db->conn->prepare('SELECT (CAST(cash AS UNSIGNED) + CAST(transfer AS UNSIGNED) + CAST(pos AS UNSIGNED)) AS total 
            FROM transaction_tbl 
            WHERE tCode = :tCode LIMIT 1
        ');
        $stmtTotalMethod->execute([':tCode' => $tCode]);
        $totalMethod = $stmtTotalMethod->fetch(PDO::FETCH_ASSOC);

        $stmtTotalAmount = $db->conn->prepare('SELECT SUM(Amount) AS total_ex 
            FROM transaction_tbl 
            WHERE tCode = :tCode
        ');
        $stmtTotalAmount->execute([':tCode' => $tCode]);
        $totalAmount = $stmtTotalAmount->fetch(PDO::FETCH_ASSOC);

        if ($totalAmount && $totalMethod) {
            if ((int)$totalAmount['total_ex'] !== (int)$totalMethod['total']) {
                throw new Exception('Total payment method must equal the total amount.');
            }
        } else {
            throw new Exception('Unable to fetch the total amounts for validation.');
        }

        // Step 3: Update stock quantities
        $stmtFetchTransactions = $db->conn->prepare('
            SELECT Product, tDepartment, qty 
            FROM transaction_tbl 
            WHERE tCode = :tCode
        ');
        $stmtFetchTransactions->execute([':tCode' => $tCode]);
        $transactions = $stmtFetchTransactions->fetchAll(PDO::FETCH_ASSOC);

        if (!$transactions) {
            throw new Exception('No transaction records found.');
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

            $newQuantity = $supply['Quantity'] - $transaction['qty'];
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
            'message' => 'Transaction validated and stock updated successfully.'
        ]);
    } catch (Exception $e) {
        $db->conn->rollBack();
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
