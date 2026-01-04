<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$_POST = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid task ID is required']);
    exit;
}

require 'db.php';
require __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';

$newStatus = $_POST['status'];
$sql = "UPDATE tasks SET status = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$newStatus, $_POST['id']])) {
    echo json_encode(['message' => 'Task status updated successfully']);

    if ($newStatus) {
        $sql = "SELECT * FROM settings WHERE id = 1";
        $stmt = $pdo->query($sql);
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($settings && $settings['notifEnabled']) {
            if ($settings['notification_type'] == 'email' && !empty($settings['email'])) {
                $mail = new PHPMailer(true);
                try {
                    // Настройки сервера
                    $mail->isSMTP();
                    $mail->Host       = $smtp_host;
                    $mail->SMTPAuth   = true;
                    $mail->Username = $smtp_user;
                    $mail->Password = $smtp_pass;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = $smtp_port;

                    // Получатель
                    $mail->setFrom($smtp_user, 'ToDo App');
                    $mail->addAddress($settings['email']);

                    // Контент
                    $mail->isHTML(true);
                    $mail->Subject = 'Задача выполнена';
                    $mail->Body    = '<p>Ваша задача с ID ' . $_POST['id'] . ' была отмечена как выполненная.</p>';
                    
                    $mail->send();
                    echo 'Письмо отправлено';
                } catch (Exception $e) {
                    echo "Ошибка отправки: {$mail->ErrorInfo} /n" . $smtp_pass . "/n" . $smtp_user;
                }
            }
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update task status']);
}
