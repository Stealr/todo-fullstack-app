<?php
$_POST = json_decode(file_get_contents('php://input'), true);

require __DIR__ . '/../db.php';

$enableNotify = isset($_POST['enable_notifications']) && $_POST['enable_notifications'] ? 1 : 0;
$typeNotify = isset($_POST['notification_method']) ? $_POST['notification_method'] : 'none';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$telegram = isset($_POST['telegram']) ? $_POST['telegram'] : '';

$sql = "INSERT INTO settings (id, notifEnabled, notification_type, email, telegram_chat_id) 
        VALUES (1, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            notifEnabled = VALUES(notifEnabled), 
            notification_type = VALUES(notification_type), 
            email = VALUES(email), 
            telegram_chat_id = VALUES(telegram_chat_id)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$enableNotify, $typeNotify, $email, $telegram])) {
    http_response_code(201);
    echo json_encode(['message' => 'Settings saved successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save settings']);
}
