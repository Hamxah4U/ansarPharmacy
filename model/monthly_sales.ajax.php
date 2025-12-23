<?php
  require 'Database.php';
  $year = $_POST['year'] ?? date('Y');

$sql = "
SELECT 
    MONTH(TransacDate) AS m,
    MONTHNAME(TransacDate) AS month,
    SUM(Amount) AS total
FROM transaction_tbl
WHERE TransacDate IS NOT NULL
  AND (Status='Paid' OR Status='paid')
  AND YEAR(TransacDate)=:year
GROUP BY MONTH(TransacDate)
ORDER BY MONTH(TransacDate)
";

$stmt = $db->conn->prepare($sql);
$stmt->execute(['year' => $year]);

$months = [
 'January','February','March','April','May','June',
 'July','August','September','October','November','December'
];
$totals = array_fill(0, 12, 0);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $index = $row['m'] - 1;
    $totals[$index] = (float)$row['total'];
}

echo json_encode([
    'months' => $months,
    'totals' => $totals
]);