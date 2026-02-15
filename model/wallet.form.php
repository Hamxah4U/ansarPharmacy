<?php
session_start();
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];
    $amount = trim($_POST['amount'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

   $stmt = $db->query("
    SELECT COALESCE(SUM(Amount),0) 
    FROM transaction_tbl 
    WHERE Status = 'Paid'
    ");
    $totalTransaction = $stmt->fetchColumn();

    $stmtWallet = $db->query("
        SELECT COALESCE(SUM(amount),0) 
        FROM wallet
    ");

    $totalWallet = $stmtWallet->fetchColumn();

    $newBal = $totalTransaction - $totalWallet;

    if ($amount > $newBal){
        $errors['amount'] = 'Insufficient amount!';
    }

    if ($amount === '') {
        $errors['amount'] = 'Amount is required!';
    }

    if ($reason === '') {
        $errors['reason'] = 'Reason is required!';
    }

    if (!empty($errors)) {
        echo json_encode([
            'status' => false,
            'errors' => $errors
        ]);
        exit;
    }

    try {
        $db = new Database();
        $stmt = $db->conn->prepare(
            "INSERT INTO wallet (amount, reason, datewithdraw, timewithdrawa, user_id)
             VALUES (:amount, :reason, NOW(), NOW(), :id)"
        );

        $stmt->execute([
            ':amount' => $amount,
            ':reason' => $reason,
            ':id' => $_SESSION['userID']
        ]);

        echo json_encode([
            'status' => true,
            'success' => ['message' => 'Withdrawal of ' . number_format($amount) . ' was successful.']
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            'status' => false,
            'errors' => ['server' => $e->getMessage()]
        ]);
        exit;
    }
}
