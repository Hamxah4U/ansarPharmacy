<?php
require 'Database.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = $_POST['tid'] ?? null;
    $returnQty = isset($_POST['return_qty']) ? intval($_POST['return_qty']) : 0;
    $reason = trim($_POST['reason'] ?? '');
    $userEmail = $_SESSION['user_email'] ?? 'System';

    if (!$tid || $returnQty < 1) {
        echo json_encode(['status' => false, 'message' => 'Invalid return quantity provided.']);
        exit;
    }

    try {
        $db->conn->beginTransaction();

        // 1. Fetch original transaction details
        $stmt = $db->conn->prepare("
            SELECT TID, tCode, Product, Price, qty, tDepartment, Customer 
            FROM transaction_tbl 
            WHERE TID = :tid FOR UPDATE
        ");
        $stmt->execute([':tid' => $tid]);
        $original = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$original) {
            $db->conn->rollBack();
            echo json_encode(['status' => false, 'message' => 'Original transaction not found.']);
            exit;
        }

        if ($returnQty > intval($original['qty'])) {
            $db->conn->rollBack();
            echo json_encode(['status' => false, 'message' => 'Return quantity cannot exceed original purchased quantity.']);
            exit;
        }

        $supplyId = $original['Product'];
        $unitPrice = floatval($original['Price']);
        $refundAmount = $unitPrice * $returnQty;

        // 2. Increase stock back into supply_tbl
        $updateStock = $db->conn->prepare("
            UPDATE supply_tbl 
            SET Quantity = Quantity + :returnQty 
            WHERE SupplyID = :supplyId
        ");
        $updateStock->execute([
            ':returnQty' => $returnQty,
            ':supplyId' => $supplyId
        ]);

        // 3. Insert negative return record into transaction_tbl for financial tracking
        $insertReturn = $db->conn->prepare("
            INSERT INTO transaction_tbl (
                tCode, Product, Price, qty, Amount, Customer, tDepartment, 
                TransacDate, TransacTime, TrasacBy, Status
            ) VALUES (
                :tCode, :product, :price, :qty, :amount, :customer, :dept, 
                CURRENT_DATE(), CURRENT_TIME(), :user, 'Returned'
            )
        ");
        $insertReturn->execute([
            ':tCode' => $original['tCode'],
            ':product' => $supplyId,
            ':price' => $unitPrice,
            ':qty' => -$returnQty,          // Negative quantity
            ':amount' => -$refundAmount,     // Negative total amount
            ':customer' => $original['Customer'],
            ':dept' => $original['tDepartment'],
            ':user' => $userEmail
        ]);

        $db->conn->commit();

        echo json_encode([
            'status' => true, 
            'message' => "Successfully returned {$returnQty} unit(s). Refund amount: ₦" . number_format($refundAmount, 2)
        ]);

    } catch (Exception $e) {
        if ($db->conn->inTransaction()) {
            $db->conn->rollBack();
        }
        echo json_encode(['status' => false, 'message' => 'Error processing return: ' . $e->getMessage()]);
    }
}
?>