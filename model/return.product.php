<?php
require 'Database.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = $_POST['tid'] ?? null;
    $returnQty = isset($_POST['return_qty']) ? intval($_POST['return_qty']) : 0;
    $userEmail = $_SESSION['email'] ?? 'System';

    if (!$tid || $returnQty < 1) {
        echo json_encode(['status' => false, 'message' => 'Invalid return details provided.']);
        exit;
    }

    try {
        $db->conn->beginTransaction();

        // 1. Fetch original transaction
        $stmt = $db->conn->prepare("
            SELECT TID, tCode, Product, Price, pprice, qty, Amount, tDepartment, Customer, cash, transfer, pos
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

        // 2. Prevent duplicate/excess returns by summing previous returns linked via CID
        $checkReturnStmt = $db->conn->prepare("
            SELECT COALESCE(SUM(ABS(qty)), 0) AS total_returned 
            FROM transaction_tbl 
            WHERE CID = :tid AND Status = 'Returned'
        ");
        $checkReturnStmt->execute([':tid' => $tid]);
        $alreadyReturned = intval($checkReturnStmt->fetch(PDO::FETCH_ASSOC)['total_returned']);

        $purchasedQty = intval($original['qty']);
        $maxReturnable = $purchasedQty - $alreadyReturned;

        if ($maxReturnable <= 0) {
            $db->conn->rollBack();
            echo json_encode(['status' => false, 'message' => 'This product has already been fully returned.']);
            exit;
        }

        if ($returnQty > $maxReturnable) {
            $db->conn->rollBack();
            echo json_encode(['status' => false, 'message' => "Cannot return {$returnQty} units. Maximum allowed remaining to return is {$maxReturnable}."]);
            exit;
        }

        // 3. Compute Negative Figures
        $unitPrice = floatval($original['Price']);
        $pPrice = floatval($original['pprice']); // Retain purchase price so profit calculates as negative
        $refundAmount = $unitPrice * $returnQty;

        // Determine payment breakdown for refund (Default: deduct directly from cash)
        $negativeCash = -$refundAmount; 

        // 4. Update Stock in supply_tbl
        $updateStock = $db->conn->prepare("
            UPDATE supply_tbl 
            SET Quantity = Quantity + :returnQty 
            WHERE SupplyID = :supplyId
        ");
        $updateStock->execute([
            ':returnQty' => $returnQty,
            ':supplyId' => $original['Product']
        ]);

        // 5. Insert Return Transaction (Link original TID via CID to prevent duplicates)
        $insertReturn = $db->conn->prepare("
            INSERT INTO transaction_tbl (
                tCode, Product, Price, pprice, qty, Amount, Customer, tDepartment, 
                TransacDate, TransacTime, TrasacBy, Status, cash, transfer, pos, CID, narration
            ) VALUES (
                :tCode, :product, :price, :pprice, :qty, :amount, :customer, :dept, 
                CURRENT_DATE(), CURRENT_TIME(), :user, 'Returned', :cash, '0', '0', :cid, 'Product Returned & Refunded'
            )
        ");
        
        $insertReturn->execute([
            ':tCode'    => $original['tCode'],
            ':product'  => $original['Product'],
            ':price'    => $unitPrice,
            ':pprice'   => $pPrice,             // Fixes generated column profit calculation
            ':qty'      => -$returnQty,         // Negative Qty restores stock reporting
            ':amount'   => -$refundAmount,      // Negative Amount reduces revenue
            ':customer' => $original['Customer'],
            ':dept'     => $original['tDepartment'],
            ':user'     => $userEmail,
            ':cash'     => strval($negativeCash), // Negative cash value to deduct cash-in totals
            ':cid'      => $original['TID']     // Links return record to prevent duplicate abuse
        ]);

        $db->conn->commit();

        echo json_encode([
            'status' => true, 
            'message' => "Return processed successfully. ₦" . number_format($refundAmount, 2) . " deducted from cash."
        ]);

    } catch (Exception $e) {
        if ($db->conn->inTransaction()) {
            $db->conn->rollBack();
        }
        echo json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>