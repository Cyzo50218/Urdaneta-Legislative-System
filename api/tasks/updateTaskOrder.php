<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost/');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) throw new Exception("User not authenticated");

    $input = json_decode(file_get_contents('php://input'), true);
    $orderIds = $input['order'] ?? null;

    if (!is_array($orderIds)) {
        throw new Exception("Invalid order data provided");
    }
    $mysqli->begin_transaction();
    $sql = "UPDATE tbl_tasks_peruser SET task_order = ? WHERE task_id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) throw new Exception($mysqli->error);

    foreach ($orderIds as $index => $taskId) {
        $position = $index + 1;
        $stmt->bind_param("iis", $position, $taskId, $userId);
        $stmt->execute();
    }

    $stmt->close();
    $mysqli->commit();

    $response['success'] = true;
    $response['message'] = "List order synchronized";

} catch (Exception $e) {
    if (isset($mysqli)) $mysqli->rollback();
    $response['error'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
$mysqli->close();