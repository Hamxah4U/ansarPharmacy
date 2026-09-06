<?php

	require 'Database.php';
    
    try {
        $stmt = $db->query('SELECT pcs_per_unit, supplyqty, wholesaleprice, SupplyDate, Pprice, department_tbl.Department AS dpt, supply_tbl.RecordedBy, supply_tbl.ExpiryDate, supply_tbl.Quantity, supply_tbl.SupplyID, supply_tbl.ProductName, supply_tbl.Price, supply_tbl.Department, supply_tbl.Status, supply_tbl.RecordedBy FROM supply_tbl INNER JOIN department_tbl ON supply_tbl.Department = department_tbl.deptID WHERE supply_tbl.Quantity > 0 GROUP BY supply_tbl.Department, ProductName, ExpiryDate ORDER BY ProductName ASC');
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Transform Quantity into "X ctn, Y pcs" format
        foreach ($products as &$product) {
            $totalPcs   = (int)($product['Quantity'] ?? 0);
            $pcsPerUnit = !empty($product['pcs_per_unit']) ? (int)$product['pcs_per_unit'] : 1;

            $fullCartons  = floor($totalPcs / $pcsPerUnit);
            $remainingPcs = $totalPcs % $pcsPerUnit;

            if ($fullCartons > 0 && $remainingPcs > 0) {
                $product['formatted_stock'] = "{$fullCartons} ctn, {$remainingPcs} pcs";
            } elseif ($fullCartons > 0) {
                $product['formatted_stock'] = "{$fullCartons} ctn";
            } else {
                $product['formatted_stock'] = "{$remainingPcs} pcs";
            }
        }

        echo json_encode($products);
    } catch(PDOException $e) {
        die('failed' . $e->getMessage());
    }
    /* require 'Database.php';
    
    try{
    	$stmt = $db->query('SELECT pcs_per_unit, supplyqty, wholesaleprice, SupplyDate, Pprice, department_tbl.Department AS dpt, supply_tbl.RecordedBy, supply_tbl.ExpiryDate, supply_tbl.Quantity, supply_tbl.SupplyID, supply_tbl.ProductName, supply_tbl.Price, supply_tbl.Department, supply_tbl.Status, supply_tbl.RecordedBy FROM supply_tbl INNER JOIN department_tbl ON supply_tbl.Department = department_tbl.deptID WHERE supply_tbl.Quantity > 0 GROUP BY supply_tbl.Department, ProductName, ExpiryDate ORDER BY ProductName ASC');
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    	echo json_encode($products);
    }catch(PDOException $e){
			die('failed'. $e->getMessage());
		} */
?>