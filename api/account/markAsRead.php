<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$notification_id = $data['id'] ?? null;

if (!$notification_id) {
    echo json_encode(['success' => false, 'message' => 'Missing notification ID']);
    exit;
}

try {
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $success = $stmt->execute([$notification_id, $_SESSION['user_id']]);

    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    error_log("MarkAsRead Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}