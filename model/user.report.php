

<?php
require 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unit = $_POST['unit'] ?? 'all';
    $product = $_POST['product'] ?? '%';
    $sdate = $_POST['sdate'] ?? '1900-01-01';
    $edate = $_POST['edate'] ?? date('Y-m-d');
    $status = $_POST['status'];
    $user = $_POST['user'] ?? 'all';
    $errors = [];

    if (empty(trim($unit))) {
        $errors['unit'] = 'Department is required!';
    }

    if (empty(trim($product))) {
        $errors['product'] = 'Product is required!';
    }

    if (empty(trim($sdate))) {
        $errors['startDate'] = 'Starting date is required!';
    }

    if (empty(trim($edate))) {
        $errors['endDate'] = 'Ending date is required!';
    }

    if (count($errors) > 0) {
        echo json_encode([
            'status' => false,
            'errors' => $errors,
        ]);
        exit;
    }

    // Query for transactions
    $sql = "SELECT `supply_tbl`.`ProductName` as dproduct, t.TID, t.tCode, d.Department AS Unit, p.Productname AS Product,
                   t.Price, t.qty, t.Amount, t.Customer, t.TransacDate, t.TransacTime, 
                   t.TrasacBy, t.Status
            FROM transaction_tbl t
            JOIN department_tbl d ON t.tDepartment = d.deptID
            LEFT JOIN product_tbl p ON t.Product = p.proID
            JOIN supply_tbl ON `t`.`Product` = `supply_tbl`.`SupplyID`
            WHERE (d.Department LIKE :unit OR :unit = 'all')
              AND (`supply_tbl`.`ProductName` LIKE :product OR :product = '%')
              AND (t.TransacDate BETWEEN :sdate AND :edate)
              AND t.Status = :status
              AND (:user = 'all' OR t.TrasacBy = :user)
              GROUP BY t.TID";
            //ORDER BY t.TransacDate DESC";
    
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([
        ':unit' => $unit === 'all' ? '%' : $unit,
        ':product' => '%' . $product . '%',
        ':sdate' => $sdate,
        ':edate' => $edate,
        ':status' => $status,
        ':user' => $user,
    ]);

    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'transactions' => $transactions,
    ]);
}
?>