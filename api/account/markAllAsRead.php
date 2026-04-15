<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($query);
    $success = $stmt->execute([$user_id]);

    echo json_encode([
        'success' => true, 
        'message' => 'All notifications marked as read'
    ]);
} catch (PDOException $e) {
    error_log("MarkAllAsRead Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}