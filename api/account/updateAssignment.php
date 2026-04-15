<?php
declare(strict_types=1);
header('Content-Type: application/json');

require_once __DIR__ . '/../connections/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false];

try {
    $adminId = $_SESSION['user_id'] ?? null;
    $trackingNo = $_POST['trackingNo'] ?? '';
    $staffUserId = $_POST['userId'] ?? '';

    if (!$adminId) throw new Exception("Unauthorized access.");
    if (empty($trackingNo) || empty($staffUserId)) throw new Exception("Missing required fields.");

    $table = '';
    $idColumn = '';
    
    if (strpos(strtoupper($trackingNo), 'RES') !== false) {
        $table = 'resolutions';
        $idColumn = 'resolution_code';
    } elseif (strpos(strtoupper($trackingNo), 'ORD') !== false) {
        $table = 'ordinances';
        $idColumn = 'ordinance_code';
    } else {
        throw new Exception("Invalid tracking number format.");
    }

    $mysqli->begin_transaction();
    $checkSql = "SELECT assigned_to FROM $table WHERE $idColumn = ? LIMIT 1";
    $checkStmt = $mysqli->prepare($checkSql);
    $checkStmt->bind_param('s', $trackingNo);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $currentRecord = $checkResult->fetch_assoc();
    
    $isAlreadyAssigned = false;
    if ($currentRecord && $currentRecord['assigned_to'] === $staffUserId) {
        $isAlreadyAssigned = true;
    }
    $updateSql = "UPDATE $table SET assigned_to = ?, assigned_date = NOW() WHERE $idColumn = ?";
    $stmt = $mysqli->prepare($updateSql);
    $stmt->bind_param('ss', $staffUserId, $trackingNo);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update record: " . $stmt->error);
    }
    $notifType = 'system';
    $notifLink = "#assigned"; 
    
    if ($isAlreadyAssigned) {
        $notifTitle = 'Task Reassigned';
        $notifMsg = "You are Reassigned on same document with tracking no: $trackingNo";
    } else {
        $notifTitle = 'New Task Assignment';
        $notifMsg = "You have been assigned to handle document: $trackingNo";
    }

    $notifSql = "INSERT INTO notifications (user_id, type, title, message, link, is_read, created_at) 
                 VALUES (?, ?, ?, ?, ?, 0, NOW())";
    $notifStmt = $mysqli->prepare($notifSql);
    $notifStmt->bind_param('sssss', $staffUserId, $notifType, $notifTitle, $notifMsg, $notifLink);
    
    if (!$notifStmt->execute()) {
        throw new Exception("Failed to send notification.");
    }

    $mysqli->commit();
    $response['success'] = true;
    $response['message'] = $isAlreadyAssigned ? "User reassigned to $trackingNo." : "Document $trackingNo assigned successfully.";

} catch (Exception $e) {
    if (isset($mysqli)) $mysqli->rollback();
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

echo json_encode($response);