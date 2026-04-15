<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Access-Control-Allow-Origin: http://localhost/'); 

require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => true, 
        'samesite' => 'Strict'
    ]);
}

$response = ['success' => false, 'data' => []];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        http_response_code(401);
        throw new Exception("Unauthorized access.");
    }
    $query = "
        SELECT 
            id,
            activity_text,
            created_at
        FROM tbl_recent_activity
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 20
    ";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Database prepare error: " . $mysqli->error);
    }

    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = [
            'id' => $row['id'],
            'text' => $row['activity_text'],
            'timestamp' => $row['created_at']
        ];
    }

    $response['success'] = true;
    $response['data'] = $activities;
    $stmt->close();

} catch (Exception $e) {
    $response['error'] = "Failed to load recent activity.";
    error_log("Fetch Activity Error: " . $e->getMessage());
    http_response_code(500);
}

echo json_encode($response);
$mysqli->close();