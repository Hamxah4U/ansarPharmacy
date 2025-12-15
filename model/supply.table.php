<?php
    require 'Database.php';
    
    try{
    	$stmt = $db->query('SELECT wholesaleprice, SupplyDate, Pprice, department_tbl.Department AS dpt, supply_tbl.RecordedBy, supply_tbl.ExpiryDate, supply_tbl.Quantity, supply_tbl.SupplyID, supply_tbl.ProductName, supply_tbl.Price, supply_tbl.Department, supply_tbl.Status, supply_tbl.RecordedBy FROM supply_tbl INNER JOIN department_tbl ON supply_tbl.Department = department_tbl.deptID WHERE supply_tbl.Quantity > 0 GROUP BY supply_tbl.Department, ProductName, ExpiryDate ORDER BY ProductName ASC');
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    	echo json_encode($products);
    }catch(PDOException $e){
			die('failed'. $e->getMessage());
		}
?>