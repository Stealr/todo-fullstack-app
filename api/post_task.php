<?php
$_POST = json_decode(file_get_contents('php://input'), true);
if (!isset($_POST['title']) || empty(trim($_POST['title']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Title is required']);
    exit;
}

require 'db.php';

$title = trim($_POST['title']);
$sql = "INSERT INTO tasks (title) VALUES (?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$title])) {
    http_response_code(201);
    echo json_encode(['message' => 'Task created successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create task']);
}
