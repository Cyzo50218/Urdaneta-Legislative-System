<?php

declare(strict_types=1);
function addRecentActivity(mysqli $mysqli, string $userId, ?int $taskId, string $text, &$errorOut = null): bool
{
    try {
        if ($taskId !== null && $taskId > 2147483647) {
            $errorOut = "Task ID $taskId is too large for MySQL INT column.";
            return false; 
        }

        $stmt = $mysqli->prepare("
            INSERT INTO tbl_recent_activity (user_id, task_id, activity_text) 
            VALUES (?, ?, ?)
        ");

        if (!$stmt) {
            $errorOut = "Prepare failed: " . $mysqli->error;
            return false;
        }

        $stmt->bind_param('sis', $userId, $taskId, $text);
        $success = $stmt->execute();
        
        if (!$success) {
            $errorOut = "Execute failed: " . $stmt->error;
        }

        $stmt->close();
        return $success;

    } catch (Exception $e) {
        $errorOut = "Exception: " . $e->getMessage();
        return false;
    }
}