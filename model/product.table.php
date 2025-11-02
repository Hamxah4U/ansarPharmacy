<?php
    require 'Database.php';
    
    try{
    	$stmt = $db->query('SELECT product_tbl.proID, product_tbl.Productname, product_tbl.Price, department_tbl.Department, product_tbl.Status, product_tbl.RegisterBy FROM product_tbl INNER JOIN department_tbl ON product_tbl.Department = department_tbl.deptID');
			$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    	echo json_encode($products);
    }catch(PDOException $e){
			die('failed'. $e->getMessage());
		}
?>