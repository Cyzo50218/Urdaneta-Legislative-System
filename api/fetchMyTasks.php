<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=60'); // short cache for faster updates

require_once __DIR__ . '/connections/connection.php';
require_once __DIR__ . '/helpers/errorHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = [
    'success' => false,
    'data' => 0
];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new Exception("User not authenticated");
    }
    $sql = "SELECT COUNT(*) AS task_count 
            FROM tbl_tasks_peruser 
            WHERE user_id = ? 
              AND status != 'Completed'";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $mysqli->error);
    }

    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $stmt->bind_result($taskCount);
    $stmt->fetch();

    $response['success'] = true;
    $response['data'] = $taskCount ?? 0;

    $stmt->close();

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    logError($mysqli, $e->getMessage(), basename(__FILE__));
    http_response_code(500);
}

echo json_encode($response);
