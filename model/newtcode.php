<?php
session_start();
require 'Database.php';

function generateTransactionCode() {
    return date('ymd') . rand(100000000, 999999999);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $newTCode = generateTransactionCode();
    echo json_encode(['newTCode' => $newTCode]);
    exit;
}
?>