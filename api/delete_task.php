<?php
$_POST = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid task ID is required']);
    exit;
}

require 'db.php';

$taskID = $_POST['id'];
$sql = "DELETE FROM tasks WHERE id = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$taskID])) {
    http_response_code(201);
    echo json_encode(['message' => 'Task deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete task']);
}