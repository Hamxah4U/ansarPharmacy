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

$errors  = [];
$success = [];

// Sanitize inputs
$subject = trim($_POST['Subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (empty($subject)) {
    $errors['Subject'] = 'Subject is required!';
}

if (empty($message)) {
    $errors['message'] = 'Message is required!';
}

// If validation fails
if (!empty($errors)) {
    echo json_encode([
        'status' => false,
        'errors' => $errors
    ]);
    exit;
}

// Insert into database
try {
    $db = new Database();

    $stmt = $db->conn->prepare(
        "INSERT INTO diary_tbl (Subject, Message, user_id) VALUES (:subject, :message, :user_id)"
    );

    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            'status'  => true,
            'success' => ['message' => 'Diary note successfully added.']
        ]);
        exit;
    }

    // If insert fails
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
