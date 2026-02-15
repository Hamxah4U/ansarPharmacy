<?php
  require 'Database.php';

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

echo json_encode([
    'balance' => number_format($newBal, 2, '.', ',')
]);
?>
