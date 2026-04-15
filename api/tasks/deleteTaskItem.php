<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: POST');
header('X-Frame-Options: DENY');

require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php';
require_once __DIR__ . '/../activity/addRecentActivity.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new Exception("User not authenticated");
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $taskId = $input['task_id'] ?? null;

    if (!$taskId) {
        throw new Exception("Missing Task ID for deletion");
    }
    $stmt = $mysqli->prepare("DELETE FROM tbl_tasks_peruser WHERE task_id = ? AND user_id = ?");
    
    if (!$stmt) {
        throw new Exception($mysqli->error);
    }

    $stmt->bind_param('is', $taskId, $userId);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    if ($affected > 0) {
        $activityText = "Task #$taskId was permanently deleted from the queue.";
        addRecentActivity($mysqli, $userId, (int)$taskId, $activityText);
        $response['success'] = true;
    } else {
        throw new Exception("Task not found or access denied.");
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    logError($mysqli, $e->getMessage(), basename(__FILE__));
    http_response_code(400); 
}

echo json_encode($response);
$mysqli->close();