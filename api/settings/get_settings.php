<?php
header('Content-Type: application/json');
require __DIR__ . '/../db.php';

$sql = "SELECT * FROM settings WHERE id = 1";
$stmt = $pdo->query($sql);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($settings) {
    echo json_encode($settings);
} else {
    echo json_encode([
        'enable_notifications' => 0,
        'notification_method' => 'none',
        'email' => '',
        'telegram' => ''
    ]);
}
