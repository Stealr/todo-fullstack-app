<?php
header('Content-Type: application/json');
require 'db.php';

$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tasks, JSON_UNESCAPED_UNICODE);