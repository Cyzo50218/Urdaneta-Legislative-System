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

    $input    = json_decode(file_get_contents('php://input'), true);
    $taskId   = $input['task_id'] ?? null;
    $status   = $input['status'] ?? null;
    $priority = $input['priority'] ?? null;
    $title    = $input['title'] ?? null;
    $dueDate  = $input['due_date'] ?? null; 

    if (!$taskId && $taskId !== 0) {
        throw new Exception("Missing Task ID");
    }

    $allowedStatus   = ['Pending', 'In Progress', 'Completed', 'Blocked'];
    $allowedPriority = ['Low', 'Medium', 'High'];

    $updateFields = [];
    $params = [];
    $types = "";

    // 1. Handle Status Update
    if ($status && in_array($status, $allowedStatus, true)) {
        $updateFields[] = "status = ?";
        $params[] = $status;
        $types .= "s";
    }

    // 2. Handle Priority Update
    if ($priority && in_array($priority, $allowedPriority, true)) {
        $updateFields[] = "priority = ?";
        $params[] = $priority;
        $types .= "s";
    }

    // 3. Handle Title Update    
    if ($title !== null) {
        $updateFields[] = "title = ?";
        $params[] = trim($title);
        $types .= "s";
    }

    // 4. Handle Due Date Update
    if ($dueDate !== null) {
        $updateFields[] = "due_date = ?";
        $params[] = $dueDate;
        $types .= "s";
    }

    if (empty($updateFields)) {
        throw new Exception("No valid fields provided for update");
    }

    $sql = "UPDATE tbl_tasks_peruser SET " . implode(", ", $updateFields) . " WHERE task_id = ? AND user_id = ?";
    $params[] = $taskId;
    $params[] = $userId;
    $types .= is_numeric($taskId) ? "is" : "ss";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception($mysqli->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close(); 

    if ($affected >= 0) {
        $activityText = "";
        
        if ($title) {
            $activityText = "Task #$taskId was renamed to: $title";
        } elseif ($dueDate) {
            $activityText = "Due date for Task #$taskId was changed to $dueDate.";
        } elseif ($priority) {
            $activityText = "Priority for Task #$taskId updated to $priority.";
        } elseif ($status) {
            $statusLower = strtolower($status);
            switch ($statusLower) {
                case 'completed': $activityText = "Task #$taskId has been marked as Completed."; break;
                case 'in progress': $activityText = "Work has started on Task #$taskId."; break;
                case 'blocked': $activityText = "Task #$taskId is currently Blocked."; break;
                case 'pending': $activityText = "Task #$taskId was moved back to Pending."; break;
            }
        }

        if (!empty($activityText)) {
            addRecentActivity($mysqli, $userId, (int)$taskId, $activityText);
        }
        
        $response['success'] = true;
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    if (function_exists('logError')) {
        logError($mysqli, $e->getMessage(), basename(__FILE__));
    }
    http_response_code(400); 
}

echo json_encode($response);
$mysqli->close();