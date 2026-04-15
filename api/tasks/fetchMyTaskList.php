<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: GET');
header('X-Frame-Options: DENY');
header('Cache-Control: public, max-age=60');

require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = [
    'success' => false,
    'data' => []
];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new Exception("User not authenticated");
    }

    $sql = "SELECT 
                task_id,
                title,
                description,
                status,
                priority,
                due_date,
                created_at
            FROM tbl_tasks_peruser
            WHERE user_id = ?
              AND status != 'Completed'
            ORDER BY 
                CASE priority 
                    WHEN 'High' THEN 1
                    WHEN 'Medium' THEN 2
                    WHEN 'Low' THEN 3
                END,
                created_at DESC
            LIMIT 6";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception($mysqli->error);
    }

    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    $response['success'] = true;
    $response['data'] = $tasks;

    $stmt->close();

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    logError($mysqli, $e->getMessage(), basename(__FILE__));
    http_response_code(500);
}

echo json_encode($response);
$mysqli->close();
