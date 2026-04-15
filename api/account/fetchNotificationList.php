<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $query = "SELECT id, type, title, message, is_read, created_at 
              FROM notifications 
              WHERE user_id = ? 
              ORDER BY created_at DESC LIMIT 20";
              
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $unread_count = 0;
    foreach ($notifications as $note) {
        if (!$note['is_read']) {
            $unread_count++;
        }
    }

    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => $unread_count
    ]);

} catch (PDOException $e) {
    // Log error for debugging but return clean JSON
    error_log("Notification Fetch Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Query Failed']);
}