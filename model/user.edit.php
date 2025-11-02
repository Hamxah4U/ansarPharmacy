<?php
	require 'Database.php';
	$id = $_GET['UID'];
	try{
    $stmt = $db->conn->prepare('SELECT * FROM `users_tbl` WHERE `userID` = :id');

		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$product = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode($product);
	}catch(PDOException $e){
		die('Error'. $e->getMessage());
	}

?>