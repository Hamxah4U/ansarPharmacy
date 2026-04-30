<?php
require 'Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tcode'])) {
    $tCode = htmlspecialchars($_POST['tcode']);
    
    $sql = 'SELECT department_tbl.Department AS store, qty, Amount, ProductName, Customer, transaction_tbl.Price AS Price,
            cash, transfer, pos
            FROM transaction_tbl
            JOIN supply_tbl ON Product = supply_tbl.SupplyID
            JOIN department_tbl ON transaction_tbl.tDepartment = department_tbl.deptID
            WHERE tCode = :tCode AND transaction_tbl.Status = "Paid"';
    
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':tCode' => $tCode]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(!empty($products)) {
        $totalAmount = 0;
        foreach($products as $row) {
            $totalAmount += $row['Amount'];
        }
        
        $html = '
        <div style="text-align: center;">
            <strong>YOUR STORE NAME</strong><br>
            <strong>Phone: 08012345678</strong><br>
            <strong>BILLING RECEIPT</strong><br>
            <hr>
        </div>
        <table>
            <tr><td><strong>TID:</strong></td><td>'.$tCode.'</td></tr>
            <tr><td><strong>Customer:</strong></td><td>'.htmlspecialchars($products[0]['Customer']).'</td></tr>
            <tr><td><strong>Date:</strong></td><td>'.date('d-M-Y h:i:s').'</td></tr>
        </table>
        <hr>
        <table>
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th><th>Amount</th></tr>
            </thead>
            <tbody>';
        
        foreach($products as $row) {
            $html .= '<tr>
                        <td>'.$row['ProductName'].'</td>
                        <td>'.$row['qty'].'</td>
                        <td>'.number_format($row['Price']).'</td>
                        <td>'.number_format($row['Amount']).'</td>
                      </tr>';
        }
        
        $html .= '<tr><td colspan="3"><strong>Total:</strong></td><td><strong>₦'.number_format($totalAmount,2).'</strong></td></tr>
        </tbody>
        </table>
        <hr>
        <div>
            <strong>Payment:</strong><br>
            Cash: ₦'.number_format($products[0]['cash'] ?? 0, 2).'<br>
            Transfer: ₦'.number_format($products[0]['transfer'] ?? 0, 2).'<br>
            POS: ₦'.number_format($products[0]['pos'] ?? 0, 2).'
        </div>
        <hr>
        <div style="text-align: center; font-size: 10px;">
            Printed By: '.($_SESSION['fname'] ?? 'Admin').'<br>
            Powered by: Tikvaah Tech Solutions
        </div>';
        
        echo json_encode(['status' => true, 'data' => $html]);
    } else {
        echo json_encode(['status' => false, 'message' => 'No data found']);
    }
}
?>