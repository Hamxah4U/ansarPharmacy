<?php
require 'Database.php';
require 'vendor/autoload.php';

header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => false,
        'errors' => ['request' => 'Invalid request method']
    ]);
    exit;
}

$errors = [];

// Sanitize inputs
$id      = $_POST['unitId'] ?? '';   // 👈 important for edit
$subject = trim($_POST['Subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (empty($subject)) {
    $errors['Subject'] = 'Subject is required!';
}

if (empty($message)) {
    $errors['message'] = 'Message is required!';
}

// Stop if validation fails
if (!empty($errors)) {
    echo json_encode([
        'status' => false,
        'errors' => $errors
    ]);
    exit;
}

try {
    $db = new Database();

    if (!empty($id)) {
        // ✅ UPDATE diary
        $stmt = $db->conn->prepare(
            "UPDATE diary_tbl 
             SET Subject = :subject, Message = :message 
             WHERE id = :id AND user_id = :user_id"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // ✅ INSERT diary
        $stmt = $db->conn->prepare(
            "INSERT INTO diary_tbl (Subject, Message, user_id) 
             VALUES (:subject, :message, :user_id)"
        );
    }

    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            'status'  => true,
            'success' => [
                'message' => !empty($id)
                    ? 'Diary note updated successfully.'
                    : 'Diary note successfully added.'
            ]
        ]);
        exit;
    }

    echo json_encode([
        'status' => false,
        'errors' => ['database' => 'Failed to save diary note']
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => false,
        'errors' => ['exception' => $e->getMessage()]
    ]);
}
