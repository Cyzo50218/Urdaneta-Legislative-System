<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once __DIR__ . '/../connections/connection.php';
require_once __DIR__ . '/../helpers/errorHelper.php';
require_once __DIR__ . '/../activity/addRecentActivity.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$response = ['success' => false];

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) throw new Exception("User not authenticated");

    $input = json_decode(file_get_contents('php://input'), true);
    $title = trim($input['title'] ?? 'New Task');
    $dueDate = $input['due_date'] ?? date('Y-m-d');

    $sql = "INSERT INTO tbl_tasks_peruser (user_id, title, status, priority, due_date) VALUES (?, ?, 'Pending', 'Medium', ?)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) throw new Exception($mysqli->error);

    $stmt->bind_param('sss', $userId, $title, $dueDate);
    $stmt->execute();
    
    $newId = $mysqli->insert_id;
    $stmt->close();

    if ($newId) {
        addRecentActivity($mysqli, $userId, $newId, "Created new task: $title");
        $response['success'] = true;
        $response['task_id'] = $newId;
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(500);
}
echo json_encode($response);