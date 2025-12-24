<?php
require 'Database.php';

$response = ['status' => false];

if (isset($_POST['id'])) {

    $db = new Database();
    $stmt = $db->conn->prepare(
        "SELECT * FROM diary_tbl WHERE id = :id"
    );
    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $response = [
            'status' => true,
            'data' => $data
        ];
    }
}

echo json_encode($response);