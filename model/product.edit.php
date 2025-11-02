<?php
	require 'Database.php';
	$id = $_GET['PID'];
	try{
		$stmt = $db->conn->prepare('SELECT * FROM `product_tbl` WHERE `proID` = :id ');
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$product = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($product);
	}catch(PDOException $e){
		die('Error'. $e->getMessage());
	}

?>