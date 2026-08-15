<?php
require 'Database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = $_POST['tid'] ?? null;
    $newPrice = isset($_POST['price']) ? floatval($_POST['price']) : null;
    $newQty = isset($_POST['qty']) ? intval($_POST['qty']) : null;

    if (!$tid || $newPrice === null || $newQty === null || $newQty < 1 || $newPrice < 0) {
        echo json_encode(['status' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    try {
        // Begin Database Transaction
        $db->conn->beginTransaction();

        // 1. Fetch current transaction record
        $stmt = $db->conn->prepare("SELECT TID, Product, Price, qty FROM transaction_tbl WHERE TID = :tid FOR UPDATE");
        $stmt->execute([':tid' => $tid]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            $db->conn->rollBack();
            echo json_encode(['status' => false, 'message' => 'Transaction record not found.']);
            exit;
        }

        $supplyId = $transaction['Product'];
        $oldQty = intval($transaction['qty']);
        $qtyDifference = $newQty - $oldQty; // Difference between new and old quantity
        $newAmount = $newPrice * $newQty;

        // 2. If quantity changed, adjust supply stock level
        if ($qtyDifference !== 0) {
            // Check stock availability if quantity increases
            if ($qtyDifference > 0) {
                $stockStmt = $db->conn->prepare("SELECT Quantity FROM supply_tbl WHERE SupplyID = :supplyId FOR UPDATE");
                $stockStmt->execute([':supplyId' => $supplyId]);
                $supply = $stockStmt->fetch(PDO::FETCH_ASSOC);

                if (!$supply || $supply['Quantity'] < $qtyDifference) {
                    $db->conn->rollBack();
                    echo json_encode(['status' => false, 'message' => 'Insufficient stock in inventory!']);
                    exit;
                }
            }

            // Deduct the net difference from supply_tbl stock
            $updateStock = $db->conn->prepare("UPDATE supply_tbl SET Quantity = Quantity - :diff WHERE SupplyID = :supplyId");
            $updateStock->execute([
                ':diff' => $qtyDifference,
                ':supplyId' => $supplyId
            ]);
        }

        // 3. Update transaction record
        $updateTrans = $db->conn->prepare("UPDATE transaction_tbl SET Price = :price, qty = :qty, Amount = :amount WHERE TID = :tid");
        $updateTrans->execute([
            ':price' => $newPrice,
            ':qty' => $newQty,
            ':amount' => $newAmount,
            ':tid' => $tid
        ]);

        // Commit Transaction
        $db->conn->commit();

        echo json_encode(['status' => true, 'message' => 'Transaction updated and stock inventory adjusted successfully!']);

    } catch (Exception $e) {
        if ($db->conn->inTransaction()) {
            $db->conn->rollBack();
        }
        echo json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>