<?php 
$_POST = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid task ID is required']);
    exit;
}

require 'db.php';

$newStatus = $_POST['status'];
$sql = "UPDATE tasks SET status = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$newStatus, $_POST['id']])) {
    echo json_encode(['message' => 'Task status updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update task status']);
}